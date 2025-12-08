<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekam;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class RekamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rekam = Rekam::with('pasien', 'dokter')->orderBy('id', 'DESC')->paginate(15);
        return view('rekam.index', compact('rekam'));
    }

    public function create($pendaftaran_id = null)
    {
        $pendaftaran = null;
        if ($pendaftaran_id) {
            $pendaftaran = Pendaftaran::with(['pasien','jadwalPoli.dokter'])->findOrFail($pendaftaran_id);
        }
        
        $dokters = Dokter::all();
        $obats = Obat::all();
        
        return view('rekam.create', compact('pendaftaran', 'dokters', 'obats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pasien' => 'required|exists:pasiens,id',
            'nomorantrian' => 'required|string',
            'layanan' => 'required|string',
            'keluhan' => 'required|string',
            'id_dokter' => 'required|exists:dokters,id',
            'diagnosa' => 'nullable|string',
            'darah' => 'nullable|string',
            'tinggi' => 'nullable|string',
            'berat' => 'nullable|string',
            'pinggang' => 'nullable|string',
        ]);

        $validated['tanggalperiksa'] = now()->toDateString();
        $validated['jadwal_kedatangan'] = now();

        $rekam = Rekam::create($validated);

        // Create prescription
        $prescription = Prescription::create([
            'rekam_id' => $rekam->id,
            'dokter_id' => $validated['id_dokter'],
            'status' => 'pending'
        ]);

        return redirect()->route('rekam.show', $rekam->id)->with('success', 'Rekam medis dibuat');
    }

    public function show($id)
    {
        $rekam = Rekam::with('pasien', 'dokter')->findOrFail($id);
        $prescription = $rekam->prescription;
        $obats = Obat::all();
        
        return view('rekam.show', compact('rekam', 'prescription', 'obats'));
    }

    public function edit($id)
    {
        $rekam = Rekam::findOrFail($id);
        $dokters = Dokter::all();
        return view('rekam.edit', compact('rekam', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'nullable|string',
            'darah' => 'nullable|string',
            'tinggi' => 'nullable|string',
            'berat' => 'nullable|string',
            'pinggang' => 'nullable|string',
        ]);

        Rekam::findOrFail($id)->update($validated);
        return redirect()->route('rekam.show', $id)->with('success', 'Rekam medis diupdate');
    }

    public function destroy($id)
    {
        Rekam::findOrFail($id)->delete();
        return back()->with('success', 'Rekam medis dihapus');
    }

    public function addObat($rekam_id, Request $request)
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric'
        ]);

        $rekam = Rekam::findOrFail($rekam_id);
        $prescription = $rekam->prescription;
        // Jika belum ada prescription untuk rekam ini, buat minimal agar bisa menambahkan obat
        if (!$prescription) {
            $prescription = Prescription::create([
                'rekam_id' => $rekam->id,
                'dokter_id' => $rekam->id_dokter ?? null,
                'status' => 'Pending'
            ]);
        }

        $harga_satuan = $validated['harga_satuan'];
        $subtotal = $harga_satuan * $validated['jumlah'];

        PrescriptionItem::create([
            'prescription_id' => $prescription->id,
            'obat_id' => $validated['obat_id'],
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $harga_satuan,
            'subtotal' => $subtotal
        ]);

        return back()->with('success', 'Obat ditambahkan ke resep');
    }
}
