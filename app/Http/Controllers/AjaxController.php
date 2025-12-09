<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPoli;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    // Search pasien by NIK, Nama, No RM
    public function searchPasien(Request $request)
    {
        $query = (string) $request->get('q', '');
        $page = max(1, (int) $request->get('page', 1));
        $limit = max(5, min(50, (int) $request->get('limit', 10)));

        if (strlen($query) < 2) {
            return response()->json(['items' => [], 'more' => false]);
        }

        $qb = Pasien::where(function($q) use ($query) {
            $q->where('nama', 'LIKE', "%{$query}%")
              ->orWhere('nik', 'LIKE', "%{$query}%")
              ->orWhere('no_rm', 'LIKE', "%{$query}%");
        })->select('id', 'no_rm', 'nama', 'nik', 'tanggal_lahir');

        $total = $qb->count();
        $items = $qb->orderBy('nama')->offset(($page-1)*$limit)->limit($limit)->get();

        $more = ($page * $limit) < $total;

        return response()->json([
            'items' => $items,
            'more' => $more,
        ]);
    }

    // Get jadwal poli by poliklinik_id
    public function getJadwalPoli($poliId)
    {
        $jadwals = JadwalPoli::with('dokter')
            ->where('poliklinik_id', $poliId)
            ->select('id', 'poliklinik_id', 'dokter_id', 'hari', 'jam_mulai', 'jam_selesai')
            ->get()
            ->map(function($j) {
                return [
                    'id' => $j->id,
                    'hari' => $j->hari,
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                    'dokter' => [
                        'id' => optional($j->dokter)->id,
                        'nama' => optional($j->dokter)->nama ?? 'Unknown',
                    ]
                ];
            });

        return response()->json($jadwals);
    }

    public function jadwalByPoli($poliId)
    {
        $jadwals = JadwalPoli::with('dokter')->where('poliklinik_id', $poliId)->get();
        return response()->json($jadwals);
    }

    // Return count of pending pendaftaran (Menunggu or Sedang Dilayani)
    public function pendingCount()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['dokter', 'admin'])) {
            return response()->json(['count' => 0]);
        }

        $count = Pendaftaran::whereIn('status_layanan', ['Menunggu', 'Sedang Dilayani'])->count();
        return response()->json(['count' => $count]);
    }

    // Get patient by exact No RM (no_rm) — used for quick lookup in forms
    public function getPatientByNoRm($noRm)
    {
        $pasien = Pasien::where('no_rm', $noRm)
            ->select('id', 'no_rm', 'kodepasien', 'nama', 'alamat', 'telepon', 'lahir', 'nik')
            ->first();

        if (!$pasien) {
            return response()->json(null, 404);
        }

        // Transform 'lahir' to 'tanggal_lahir' for frontend
        $pasien->tanggal_lahir = $pasien->lahir;
        
        return response()->json($pasien);
    }

    // Suggest No RM values for autocomplete (returns array of matching no_rm)
    public function suggestNoRm(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $list = Pasien::where('no_rm', 'LIKE', "%{$q}%")
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->pluck('no_rm');

        return response()->json($list);
    }
}
