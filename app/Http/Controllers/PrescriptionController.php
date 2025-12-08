<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Rekam;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Obat;
use App\Models\Dokter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    private function checkDokterAccess()
    {
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            abort(403, 'Hanya dokter yang dapat membuat resep');
        }
    }
    
    private function checkApotekerAccess()
    {
        if (!in_array(auth()->user()->role, ['apoteker', 'admin'])) {
            abort(403, 'Hanya apoteker yang dapat mengelola resep');
        }
    }

    /**
     * Show prescription form for a rekam medis (examination)
     */
    public function create($rekamId)
    {
        $this->checkDokterAccess();
        $rekam = Rekam::with('pasien', 'dokter', 'pendaftaran.poliklinik', 'tindakan')->findOrFail($rekamId);
        $obat = Obat::all();
        
        return view('prescription.create', compact('rekam', 'obat'));
    }

    /**
     * Store prescription with items
     */
    public function store($rekamId, Request $request)
    {
        $this->checkDokterAccess();
        $rekam = Rekam::findOrFail($rekamId);
        
        $validated = $request->validate([
            'obat_items' => 'required|array|min:1',
            'obat_items.*.obat_id' => 'required|exists:obats,id',
            'obat_items.*.dosis' => 'required|string',
            'obat_items.*.jumlah' => 'required|integer|min:1',
            'catatan_resep' => 'nullable|string|max:500'
        ]);

        try {
            // Create Prescription
            $prescription = Prescription::create([
                'rekam_id' => $rekam->id,
                'dokter_id' => optional(auth()->user()->dokter)->id ?? null,
                'status' => 'Pending'
            ]);

            // Create Prescription Items
            $totalHarga = 0;
            foreach ($validated['obat_items'] as $item) {
                $obat = Obat::find($item['obat_id']);
                
                if (!$obat) {
                    throw new \Exception('Obat tidak ditemukan');
                }

                $subtotal = ($obat->harga ?? 0) * $item['jumlah'];
                $totalHarga += $subtotal;

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'obat_id' => $item['obat_id'],
                    'jumlah' => $item['jumlah'],
                    'dosis' => $item['dosis'],
                    'harga_satuan' => $obat->harga ?? 0,
                    'subtotal' => $subtotal
                ]);
            }

            // Update Rekam with prescription data
            $rekam->update([
                'resep_status' => 'Sudah Dibuat',
                'resep_catatan' => $validated['catatan_resep'] ?? null
            ]);

            // Update Pendaftaran status to Selesai (jika tersedia)
            if (optional($rekam->pendaftaran)) {
                try {
                    optional($rekam->pendaftaran)->update([
                        'status_layanan' => 'Selesai'
                    ]);
                } catch (\Exception $e) {
                    // jika update pendaftaran gagal, lanjutkan tapi catat log
                    logger()->warning('Gagal update pendaftaran untuk rekam id ' . $rekam->id . ': ' . $e->getMessage());
                }
            }

            return redirect()->route('rekam.show', $rekam->id)
                ->with('success', 'Resep berhasil dibuat! Total: Rp ' . number_format($totalHarga, 0, ',', '.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show prescription details
     */
    public function show($prescriptionId)
    {
        $this->checkApotekerAccess();
        $prescription = Prescription::with('items.obat', 'rekam.pasien', 'dokter')->findOrFail($prescriptionId);
        
        return view('prescription.show', compact('prescription'));
    }

    /**
     * List all prescriptions
     */
    public function index()
    {
        $this->checkApotekerAccess();
        $prescriptions = Prescription::with('rekam.pasien', 'dokter')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);
        
        return view('prescription.index', compact('prescriptions'));
    }

    /**
     * List pending prescriptions for apotik
     */
    public function pending()
    {
        $this->checkApotekerAccess();
        $prescriptions = Prescription::with('items.obat', 'rekam.pasien', 'dokter')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'ASC')
            ->paginate(15);
        
        return view('prescription.pending', compact('prescriptions'));
    }

    /**
     * Apotik processes/fulfills prescription with auto stock reduction
     */
    public function process($prescriptionId, Request $request)
    {
        $this->checkApotekerAccess();
        $prescription = Prescription::with('items.obat')->findOrFail($prescriptionId);
        
        if ($prescription->status !== 'Pending') {
            return back()->with('error', 'Resep sudah diproses sebelumnya');
        }

        try {
            DB::transaction(function () use ($prescription) {
                // Auto-reduce stok untuk setiap item obat
                foreach ($prescription->items as $item) {
                    $obat = $item->obat;

                    // Validasi stok tersedia
                    if (!$obat || $obat->stok < $item->jumlah) {
                        throw new \Exception("Stok obat '{$obat->nama}' tidak cukup. Tersedia: {$obat->stok}, Diminta: {$item->jumlah}");
                    }

                    // Reduce stok
                    $obat->decrement('stok', $item->jumlah);
                }

                // Update prescription status
                $prescription->update(['status' => 'Diberikan']);

                // Create invoice for this prescription so kasir can process
                $this->createInvoiceFromPrescription($prescription);

                // Update Pendaftaran if exists
                if (optional($prescription->rekam)->pendaftaran) {
                    optional($prescription->rekam)->pendaftaran->update(['status_layanan' => 'Selesai']);
                }
            });

            return back()->with('success', 'Resep berhasil diberikan, stok dikurangi, dan invoice dibuat');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update prescription status (for apotik/admin)
     */
    public function updateStatus($prescriptionId, Request $request)
    {
        $this->checkApotekerAccess();
        $prescription = Prescription::findOrFail($prescriptionId);
        
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diberikan,Ditolak'
        ]);

            try {
                DB::transaction(function () use ($prescription, $validated) {
                    // If changing to Diberikan, auto-reduce stok and create invoice
                    if ($validated['status'] === 'Diberikan' && $prescription->status !== 'Diberikan') {
                        $prescription->load('items.obat');

                        foreach ($prescription->items as $item) {
                            $obat = $item->obat;

                            if (!$obat || $obat->stok < $item->jumlah) {
                                throw new \Exception("Stok obat '{$obat->nama}' tidak cukup");
                            }

                            $obat->decrement('stok', $item->jumlah);
                        }

                        $prescription->update(['status' => 'Diberikan']);

                        // create invoice
                        $this->createInvoiceFromPrescription($prescription);
                    } else {
                        $prescription->update(['status' => $validated['status']]);
                    }
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Status resep berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
    }

    /**
     * Tampilkan daftar resep yang siap diberikan (dispensing queue)
     */
    public function dispensingQueue()
    {
        $this->checkApotekerAccess();
        $prescriptions = Prescription::with('rekam.pasien', 'rekam.dokter')
            ->where('status', '!=', 'Diberikan')
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('dispensing.queue', compact('prescriptions'));
    }

    /**
     * Form dispensing untuk memberikan obat ke pasien
     */
    public function dispensingForm($prescriptionId)
    {
        $this->checkApotekerAccess();
        $prescription = Prescription::with('rekam.pasien', 'items.obat')->findOrFail($prescriptionId);
        
        return view('dispensing.form', compact('prescription'));
    }

    /**
     * Confirm dispensing dan kurangi stok obat
     */
    public function confirmDispensingController(Request $request, $prescriptionId)
    {
        $this->checkApotekerAccess();
        $prescription = Prescription::with('items.obat')->findOrFail($prescriptionId);
        
        try {
            DB::transaction(function () use ($prescription) {
                // Kurangi stok untuk setiap item
                foreach ($prescription->items as $item) {
                    $obat = $item->obat;

                    if (!$obat) {
                        throw new \Exception('Obat tidak ditemukan');
                    }

                    if ($obat->stok < $item->jumlah) {
                        throw new \Exception('Stok ' . $obat->nama . ' tidak cukup. Stok tersedia: ' . $obat->stok);
                    }

                    // Kurangi stok
                    $obat->decrement('stok', $item->jumlah);
                }

                // Update status resep menjadi "Diberikan"
                $prescription->update(['status' => 'Diberikan']);

                // Create invoice for cashier
                $this->createInvoiceFromPrescription($prescription);
            });

            return redirect()->route('dispensing.queue')
                ->with('success', 'Obat berhasil diberikan ke pasien dan invoice dibuat');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Create invoice and invoice items from a given prescription
     */
    private function createInvoiceFromPrescription(Prescription $prescription)
    {
        // ensure related rekam and pasien loaded
        $prescription->loadMissing('rekam', 'rekam.pasien', 'items.obat');

        $rekam = $prescription->rekam;

        if (!$rekam) {
            throw new \Exception('Rekam medis terkait tidak ditemukan untuk pembuatan invoice');
        }

        // prepare invoice values: include obat + tindakan
        $obatTotal = 0;
        foreach ($prescription->items as $item) {
            $obatTotal += $item->subtotal ?? (($item->harga_satuan ?? 0) * ($item->jumlah ?? 0));
        }

        // tindakan (from rekam->tindakan)
        $rekam->loadMissing('tindakan');
        $tindakanTotal = 0;
        foreach ($rekam->tindakan as $t) {
            $tindakanTotal += $t->harga ?? 0;
        }

        $subtotalAll = $obatTotal + $tindakanTotal;

        // determine payment type: prefer pendaftaran.jenis_pembayaran, fallback to pasien.jenis_pasien
        $jenisPembayaran = data_get($rekam, 'pendaftaran.jenis_pembayaran') ?: data_get($rekam, 'pasien.jenis_pasien') ?: 'tunai';

        // If BPJS or Asuransi, mark accordingly (total 0 for patient)
        $isCovered = in_array(strtolower($jenisPembayaran), ['bpjs', 'asuransi']);

        $invoice = Invoice::create([
            'rekam_id' => $rekam->id,
            'pasien_id' => $rekam->id_pasien,
            'layanan' => 'Pemeriksaan + Resep',
            'jenis_pembayaran' => $jenisPembayaran,
            'no_bpjs' => data_get($rekam, 'pendaftaran.no_bpjs'),
            'keterangan_pembayaran' => $isCovered && data_get($rekam, 'pendaftaran.no_bpjs') ? 'No BPJS: ' . data_get($rekam, 'pendaftaran.no_bpjs') : null,
            'subtotal' => $subtotalAll,
            'total' => $isCovered ? 0 : $subtotalAll,
            'status' => $isCovered ? ('paid_by_' . strtolower($jenisPembayaran)) : 'unpaid'
        ]);

        // add tindakan items
        foreach ($rekam->tindakan as $t) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $t->nama ?? 'Tindakan',
                'type' => 'tindakan',
                'amount' => $t->harga ?? 0
            ]);
        }

        // add obat items
        foreach ($prescription->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => optional($item->obat)->nama ?? 'Obat',
                'type' => 'obat',
                'amount' => $item->subtotal ?? (($item->harga_satuan ?? 0) * ($item->jumlah ?? 0))
            ]);
        }

        return $invoice;
    }
}
