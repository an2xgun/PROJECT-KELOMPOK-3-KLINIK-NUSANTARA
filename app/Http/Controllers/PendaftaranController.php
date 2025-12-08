<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\JadwalPoli;
use App\Models\Rekam;

class PendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman pilihan: pasien baru atau lama
    public function choice()
    {
        return view('pendaftaran.choice');
    }

    // Halaman cari pasien lama
    public function searchOld()
    {
        return view('pendaftaran.search-old');
    }

    // Halaman input pasien baru
    public function createNew()
    {
        // Pre-generate next No RM to show in the form
        $nextNoRm = \App\Models\Pasien::peekNextNoRm();
        return view('pendaftaran.create-new', compact('nextNoRm'));
    }

    // Simpan pasien baru dan lanjut ke pilih poli
    public function storeNew(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nik' => 'required|string|unique:pasiens,nik',
            'kelamin' => 'required|in:Laki-laki,Perempuan',
            'lahir' => 'required|date',
            'golongan_darah' => 'required|in:O,A,B,AB',
            'jenis_pasien' => 'required|in:Umum,Asuransi,BPJS',
            'agama' => 'required|string',
            'pendidikan' => 'nullable|string',
            'provinsi' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        // Generate No RM (0001, 0002, etc.)
        $noRm = Pasien::generateNextNoRm();

        // Create pasien with all fields
        $pasien = Pasien::create(array_merge($validated, ['no_rm' => $noRm]));

        return redirect()->route('pendaftaran.select-poli', $pasien->id);
    }

    // Halaman pilih poli & jadwal
    public function selectPoli(Pasien $pasien)
    {
        $polikliniks = Poliklinik::all();

        // Ambil jadwal per poliklinik sebagai fallback server-side
        $jadwals = JadwalPoli::with('dokter')
            ->get()
            ->groupBy('poliklinik_id')
            ->mapWithKeys(function($group, $key) {
                $items = $group->map(function($j) {
                    return [
                        'id' => $j->id,
                        'hari' => $j->hari,
                        'jam_mulai' => $j->jam_mulai,
                        'jam_selesai' => $j->jam_selesai,
                        'dokter' => [
                            'id' => $j->dokter->id ?? null,
                            'nama' => $j->dokter->nama ?? 'Unknown',
                        ],
                    ];
                })->values()->toArray();

                return [(string)$key => $items];
            })->toArray();

        return view('pendaftaran.select-poli', compact('pasien', 'polikliniks', 'jadwals'));
    }

    // Simpan pendaftaran poli dan buat rekam medis
    public function storePoli(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'poliklinik_id' => 'required|exists:polikliniks,id',
            'jadwal_poli_id' => 'required|exists:jadwal_polis,id',
            'keluhan' => 'required|string',
            'jenis_pembayaran' => 'required|in:Umum,BPJS,Asuransi',
            'no_bpjs' => 'required_if:jenis_pembayaran,BPJS,Asuransi|nullable|string',
            'tanggal_kunjungan' => 'required|date',
        ]);

        // Generate nomor antrian
        $count = Pendaftaran::where('poliklinik_id', $validated['poliklinik_id'])
            ->whereDate('created_at', now())
            ->count();

        $nomorAntrian = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        // Simpan pendaftaran beserta fields tambahan
        $pendaftaran = Pendaftaran::create([
            'pasien_id' => $pasien->id,
            'poliklinik_id' => $validated['poliklinik_id'],
            'jadwal_poli_id' => $validated['jadwal_poli_id'],
            'nomor_antrian' => $nomorAntrian,
            'keluhan' => $validated['keluhan'],
            'jenis_pembayaran' => $validated['jenis_pembayaran'],
            'no_bpjs' => $validated['no_bpjs'] ?? null,
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'status_layanan' => 'Menunggu',
        ]);

        // Do NOT create Rekam here. Pendaftaran hanya sampai registrasi ke poli.
        // Dokter/perawat will perform pemeriksaan later via the Examination queue.
        return redirect()->route('pendaftaran.list')
                       ->with('success', 'Pendaftaran berhasil. Silakan tunggu panggilan untuk pemeriksaan.');
    }

    // Daftar pendaftaran
    public function index()
    {
        $pendaftaran = Pendaftaran::with('pasien', 'poliklinik', 'jadwalPoli.dokter')
            ->orderBy('created_at', 'DESC')
            ->paginate(15);
        
        return view('pendaftaran.index', compact('pendaftaran'));
    }

    // Tandai sebagai sedang dilayani
    public function serve(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status_layanan' => 'Sedang Dilayani']);

        return back()->with('success', 'Status diubah menjadi Sedang Dilayani');
    }

    // Hapus pendaftaran
    public function destroy($id)
    {
        Pendaftaran::findOrFail($id)->delete();
        return back()->with('success', 'Pendaftaran dihapus');
    }
}
