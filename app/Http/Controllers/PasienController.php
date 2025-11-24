<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Poliklinik;
use Carbon\Carbon;

class PasienController extends Controller
{
   
   public function index(Request $r)
{
    $query = Pasien::orderBy('id', 'DESC');

    if ($r->q) {
        $query->where('nama', 'like', '%'.$r->q.'%')
              ->orWhere('no_rm', 'like', '%'.$r->q.'%');
    }

    $data = $query->paginate(15);

    return view('pasien.index', compact('data'));
}



    public function create()
    {
        $last = Pasien::orderBy('id','DESC')->first();
        $no_rm = $last ? str_pad($last->id + 1, 6, '0', STR_PAD_LEFT) : '000001';

        return view('pasien.create', [
            'title' => 'Data Pasien Baru',
            'poli'  => Poliklinik::all(),
            'no_rm' => $no_rm
        ]);
    }

    public function store(Request $r)
    {
        // Hitung umur
        $tgl = Carbon::parse($r->tanggal_lahir);
        $now = Carbon::now();

        $r['umur_tahun'] = $tgl->diffInYears($now);
        $r['umur_bulan'] = $tgl->diffInMonths($now) % 12;
        $r['umur_hari']  = $tgl->diffInDays($now) % 30;

        Pasien::create($r->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('pasien.edit', [
            'title'  => 'Edit Pasien',
            'pasien' => Pasien::findOrFail($id),
            'poli'   => Poliklinik::all(),
        ]);
    }

    public function update(Request $r, $id)
    {
        // Recalculate umur
        $tgl = Carbon::parse($r->tanggal_lahir);
        $now = Carbon::now();

        $r['umur_tahun'] = $tgl->diffInYears($now);
        $r['umur_bulan'] = $tgl->diffInMonths($now) % 12;
        $r['umur_hari']  = $tgl->diffInDays($now) % 30;

        Pasien::findOrFail($id)->update($r->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien diupdate');
    }

    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
        return back()->with('success', 'Data pasien dihapus');
    }

    // AJAX Search untuk Pendaftaran
    public function getByNoRM($no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->first();
        return response()->json($pasien);
    }
}
