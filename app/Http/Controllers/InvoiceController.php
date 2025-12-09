<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Rekam;
use App\Models\Pasien;
use App\Models\Pendaftaran;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        $query = Invoice::with('pasien', 'rekam')->orderBy('id', 'DESC');

        if ($r->status) {
            $query->where('status', $r->status);
        }

        $invoices = $query->paginate(15);
        return view('invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with('items', 'pasien', 'rekam')->findOrFail($id);
        return view('invoice.show', compact('invoice'));
    }

    public function create($rekam_id)
    {
        $rekam = Rekam::with('pasien', 'prescription.items.obat')->findOrFail($rekam_id);
        
        return view('invoice.create', compact('rekam'));
    }

    // Create invoice from Pendaftaran (bypass pemeriksaan/rekam medis)
    public function createFromPendaftaran($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with('pasien', 'poliklinik')->findOrFail($pendaftaran_id);
        
        // Return view for invoice creation without rekam medis
        return view('invoice.create-from-pendaftaran', compact('pendaftaran'));
    }

    public function store(Request $request, $rekam_id)
    {
        $validated = $request->validate([
            'layanan' => 'nullable|string',
            'jenis_pembayaran' => 'nullable|string',
            'no_bpjs' => 'nullable|string',
            'keterangan_pembayaran' => 'nullable|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.type' => 'required|string',
            'items.*.amount' => 'required|numeric'
        ]);

        // Validate BPJS/Asuransi number format if provided
        if (!empty($validated['no_bpjs'])) {
            $method = $validated['jenis_pembayaran'] ?? '';
            if ($method === 'BPJS' && !preg_match('/^\d{13}$/', $validated['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor BPJS harus tepat 13 digit angka'])->withInput();
            }
            if ($method === 'Asuransi' && !preg_match('/^[a-zA-Z0-9]{6,}$/', $validated['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor Asuransi minimal 6 karakter (huruf/angka)'])->withInput();
            }
        }

        $rekam = Rekam::findOrFail($rekam_id);
        
        // Filter items yang memiliki checkbox include yang di-check
        // Jika tidak ada checkbox include field, berarti semua item yang dikirim harus ditambahkan
        $toBill = [];
        $hasIncludeField = false;
        
        foreach ($validated['items'] as $item) {
            // Cek apakah ada field 'include' di request raw
            $itemIndex = array_search($item, $validated['items']);
            if (isset($request->input('items')[$itemIndex]['include'])) {
                $hasIncludeField = true;
                $toBill[] = $item;
            }
        }
        
        // Jika tidak ada field include sama sekali, gunakan semua items
        if (!$hasIncludeField) {
            $toBill = $validated['items'];
        }

        if (count($toBill) === 0) {
            return back()->with('error', 'Tidak ada item yang dipilih untuk ditagihkan');
        }

        $subtotal = 0;
        foreach ($toBill as $item) {
            $subtotal += $item['amount'];
        }

        $invoice = Invoice::create([
            'rekam_id' => $rekam_id,
            'pasien_id' => $rekam->id_pasien,
            'layanan' => $validated['layanan'] ?? null,
            'jenis_pembayaran' => $validated['jenis_pembayaran'] ?? null,
            'no_bpjs' => $validated['no_bpjs'] ?? null,
            'keterangan_pembayaran' => $validated['keterangan_pembayaran'] ?? null,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status' => 'unpaid'
        ]);

        foreach ($toBill as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $item['name'],
                'type' => $item['type'],
                'amount' => $item['amount']
            ]);
        }

        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice dibuat');
    }

    // Store invoice from Pendaftaran (simple service charge)
    public function storeFromPendaftaran(Request $request, $pendaftaran_id)
    {
        $validated = $request->validate([
            'layanan' => 'nullable|string',
            'jenis_pembayaran' => 'nullable|string',
            'no_bpjs' => 'nullable|string',
            'keterangan_pembayaran' => 'nullable|string',
            'biaya_layanan' => 'required|numeric|min:0'
        ]);

        // Validate BPJS/Asuransi number format if provided
        if (!empty($validated['no_bpjs'])) {
            $method = $validated['jenis_pembayaran'] ?? '';
            if ($method === 'BPJS' && !preg_match('/^\d{13}$/', $validated['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor BPJS harus tepat 13 digit angka'])->withInput();
            }
            if ($method === 'Asuransi' && !preg_match('/^[a-zA-Z0-9]{6,}$/', $validated['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor Asuransi minimal 6 karakter (huruf/angka)'])->withInput();
            }
        }

        $pendaftaran = Pendaftaran::findOrFail($pendaftaran_id);
        $amount = $validated['biaya_layanan'];

        $invoice = Invoice::create([
            'rekam_id' => null, // No rekam medis for this invoice
            'pasien_id' => $pendaftaran->pasien_id,
            'layanan' => $validated['layanan'] ?? 'Pendaftaran & Konsultasi',
            'jenis_pembayaran' => $validated['jenis_pembayaran'] ?? null,
            'no_bpjs' => $validated['no_bpjs'] ?? null,
            'keterangan_pembayaran' => $validated['keterangan_pembayaran'] ?? null,
            'subtotal' => $amount,
            'total' => $amount,
            'status' => 'unpaid'
        ]);

        // Add service charge as line item
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'name' => $validated['layanan'] ?? 'Biaya Layanan Pendaftaran',
            'type' => 'layanan',
            'amount' => $amount
        ]);

        return redirect()->route('invoice.show', $invoice->id)->with('success', 'Invoice dari pendaftaran dibuat');
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = request()->validate([
            'payment_method' => 'nullable|string',
            'no_bpjs' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        // Validate BPJS/Asuransi number format if provided
        if (!empty($data['no_bpjs'])) {
            $method = $data['payment_method'] ?? '';
            if ($method === 'BPJS' && !preg_match('/^\d{13}$/', $data['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor BPJS harus tepat 13 digit angka'])->withInput();
            }
            if ($method === 'Asuransi' && !preg_match('/^[a-zA-Z0-9]{6,}$/', $data['no_bpjs'])) {
                return back()->withErrors(['no_bpjs' => 'Nomor Asuransi minimal 6 karakter (huruf/angka)'])->withInput();
            }
        }

        // Save payment info
        if (!empty($data['no_bpjs'])) {
            $invoice->no_bpjs = $data['no_bpjs'];
        }
        if (!empty($data['keterangan'])) {
            $invoice->keterangan_pembayaran = $data['keterangan'];
        }

        // Determine status label
        $method = $data['payment_method'] ?? 'Tunai';
        if (in_array(strtolower($method), ['bpjs','asuransi'])) {
            // mark as covered/paid by insurance
            $invoice->status = 'paid_by_' . strtolower($method);
        } else {
            $invoice->status = 'paid';
        }
        $invoice->paid_at = now();
        $invoice->save();

        // Create payment record (history)
        $amount = $invoice->total ?? ($invoice->subtotal ?? 0);
        \App\Models\Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'method' => $method,
            'no_bpjs' => $data['no_bpjs'] ?? null,
            'note' => $data['keterangan'] ?? null,
            'amount' => $amount,
            'paid_at' => now()
        ]);

        return back()->with('success', 'Invoice diproses, pembayaran dicatat, dan ditandai sebagai dibayar');
    }

    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();
        return back()->with('success', 'Invoice dihapus');
    }

    public function print($id)
    {
        $invoice = Invoice::with('items', 'pasien', 'rekam')->findOrFail($id);
        return view('invoice.print', compact('invoice'));
    }

    public function printThermal($id)
    {
        $invoice = Invoice::with('items', 'pasien', 'rekam')->findOrFail($id);
        return view('invoice.print_thermal', compact('invoice'));
    }
}
