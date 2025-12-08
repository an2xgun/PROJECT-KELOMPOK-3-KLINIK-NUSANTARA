<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Rekam;
use App\Models\MasterTindakan;
use App\Models\MasterDiagnosa;
use App\Models\Poliklinik;
use Illuminate\Http\Request;

class ExaminationFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show examination form
     */
    public function show($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'jadwalPoli'])->findOrFail($pendaftaranId);
        
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $rekam = Rekam::where('id_pasien', $pendaftaran->pasien_id)
            ->where('nomorantrian', $pendaftaran->nomor_antrian)
            ->first();

        $tindakan = MasterTindakan::all();
        $diagnosa = MasterDiagnosa::all();

        return view('examination.umum_form', [
            'pendaftaran' => $pendaftaran,
            'rekam' => $rekam,
            'tindakan' => $tindakan,
            'diagnosa' => $diagnosa,
        ]);
    }

    /**
     * Show detail pemeriksaan dengan form inline untuk edit status
     */
    public function detail($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'jadwalPoli'])->findOrFail($pendaftaranId);
        
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $rekam = Rekam::where('id_pasien', $pendaftaran->pasien_id)
            ->where('nomorantrian', $pendaftaran->nomor_antrian)
            ->first();

        $tindakan = MasterTindakan::all();
        $diagnosa = MasterDiagnosa::all();

        return view('examination.detail', [
            'pendaftaran' => $pendaftaran,
            'rekam' => $rekam,
            'tindakan' => $tindakan,
            'diagnosa' => $diagnosa,
        ]);
    }

    /**
     * Update status pemeriksaan (dari antrian)
     */
    public function updateStatus(Request $request, $rekamId)
    {
        $rekam = Rekam::findOrFail($rekamId);
        
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        $validated = $request->validate([
            'status_pemeriksaan' => 'nullable|in:Belum Diperiksa,Sedang Diperiksa,Sudah Diperiksa,Ditolak',
            'catatan_status' => 'nullable|string|max:500',
        ]);

        $rekam->update([
            'status_pemeriksaan' => $validated['status_pemeriksaan'] ?? 'Belum Diperiksa',
            'catatan_status' => $validated['catatan_status'] ?? null,
        ]);

        return back()->with('success', 'Status pemeriksaan berhasil diperbarui');
    }

    /**
     * Show queue of pending pendaftaran for dokter to perform pemeriksaan
     */
    public function queue()
    {
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        // List pendaftarans that are not finished yet
        $pendaftarans = Pendaftaran::with(['pasien', 'poliklinik', 'jadwalPoli.dokter'])
            ->whereIn('status_layanan', ['Menunggu', 'Sedang Dilayani'])
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('examination.queue', compact('pendaftarans'));
    }

    /**
     * Store examination results
     */
    public function store(Request $request, $pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with('pasien', 'poliklinik')->findOrFail($pendaftaranId);
        
        if (!in_array(auth()->user()->role, ['dokter', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $validated = $request->validate([
            'keluhan_utama' => 'required|string|max:500',
            'anamnesis' => 'nullable|string|max:1000',
            'pemeriksaan_fisik' => 'nullable|string|max:1000',
            'tinggi' => 'nullable|numeric',
            'berat' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
            'darah' => 'nullable|string|max:50',
            'diagnosa_primer' => 'nullable|exists:master_diagnosa,id',
            'diagnosa_sekunder' => 'nullable|exists:master_diagnosa,id',
            'tindakan_ids' => 'nullable|array',
            'tindakan_ids.*' => 'nullable|exists:master_tindakan,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        $rekam = Rekam::where('id_pasien', $pendaftaran->pasien_id)
            ->where('nomorantrian', $pendaftaran->nomor_antrian)
            ->first();

        if (!$rekam) {
            // Prefer dokter from pendaftaran jadwal if available
            $dokterId = optional($pendaftaran->jadwalPoli)->dokter_id ?? null;
            // Fallback: if the authenticated user is a dokter and we have a mapping, use it.
            if (!$dokterId && auth()->check()) {
                // try to find a Dokter record that matches current user by name (best-effort)
                try {
                    $user = auth()->user();
                    if ($user) {
                        $dok = \App\Models\Dokter::where('nama', 'LIKE', "%{$user->name}%")->first();
                        if ($dok) $dokterId = $dok->id;
                    }
                } catch (\Exception $e) {
                    // ignore and leave dokterId null
                }
            }

            $rekam = Rekam::create([
                'laporan' => 0,
                'id_pasien' => $pendaftaran->pasien_id,
                'pendaftaran_id' => $pendaftaran->id,
                'nomorantrian' => $pendaftaran->nomor_antrian,
                'tanggalperiksa' => now()->toDateString(),
                'layanan' => optional($pendaftaran->poliklinik)->name ?? 'UMUM',
                'keluhan' => $validated['keluhan_utama'] ?? '',
                'id_dokter' => $dokterId,
                'tinggi' => $validated['tinggi'] ?? null,
                'berat' => $validated['berat'] ?? null,
                'suhu' => $validated['suhu'] ?? null,
                'darah' => $validated['darah'] ?? null,
                'diagnosa_primer' => $validated['diagnosa_primer'] ?? null,
                'diagnosa_sekunder' => $validated['diagnosa_sekunder'] ?? null,
            ]);
        } else {
            $rekam->update([
                'keluhan' => $validated['keluhan_utama'] ?? $rekam->keluhan,
                'id_dokter' => optional($pendaftaran->jadwalPoli)->dokter_id ?? $rekam->id_dokter,
                'tinggi' => $validated['tinggi'] ?? $rekam->tinggi,
                'berat' => $validated['berat'] ?? $rekam->berat,
                'suhu' => $validated['suhu'] ?? $rekam->suhu,
                'darah' => $validated['darah'] ?? $rekam->darah,
                'diagnosa_primer' => $validated['diagnosa_primer'] ?? $rekam->diagnosa_primer,
                'diagnosa_sekunder' => $validated['diagnosa_sekunder'] ?? $rekam->diagnosa_sekunder,
            ]);
        }

        // handle diagnoses fields
        if (isset($validated['diagnosa_primer'])) {
            $rekam->diagnosa_primer = $validated['diagnosa_primer'];
        }
        if (isset($validated['diagnosa_sekunder'])) {
            $rekam->diagnosa_sekunder = $validated['diagnosa_sekunder'];
        }

        // handle tindakan (multiple)
        if (!empty($validated['tindakan_ids']) && is_array($validated['tindakan_ids'])) {
            $rekam->tindakan()->sync($validated['tindakan_ids']);
        }

        $rekam->keterangan = $validated['catatan'] ?? $rekam->keterangan;
        $rekam->save();

        $pendaftaran->update(['status_layanan' => 'Selesai']);

        return redirect()->route('prescription.create', $rekam->id)
            ->with('success', 'Data pemeriksaan berhasil disimpan');
    }

}
