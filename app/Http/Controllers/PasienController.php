<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Poliklinik;
use Carbon\Carbon;

class PasienController extends Controller
{
    public function index()
    {
        return view('pasien.index', [
            'title' => 'Data Pasien',
            'pasien' => Pasien::orderBy('id','DESC')->paginate(20)
        ]);
    }

    public function create()
    {
        // generate nomor rekam medis otomatis
        $last = Pasien::orderBy('id','DESC')->first();
        $no_rm = $last ? $last->id + 1 : 1;
        $no_rm = str_pad($no_rm, 6, '0', STR_PAD_LEFT);

        return view('pendaftaran.create', [
            'title' => 'Pasien Baru',
            'poli'  => Poliklinik::all(),
            'no_rm' => $no_rm
        ]);
    }

    public function store(Request $r)
    {
        // hitung umur otomatis
        $tgl = Carbon::parse($r->tanggal_lahir);
        $now = Carbon::now();

        $umur_tahun = $tgl->diffInYears($now);
        $umur_bulan = $tgl->copy()->addYears($umur_tahun)->diffInMonths($now);
        $umur_hari  = $tgl->copy()->addYears($umur_tahun)->addMonths($umur_bulan)->diffInDays($now);

        $data = $r->all();
        $data['umur_tahun'] = $umur_tahun;
        $data['umur_bulan'] = $umur_bulan;
        $data['umur_hari']  = $umur_hari;
        $data['tanggal_kunjungan'] = now();

        Pasien::create($data);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('pasien.edit', [
            'title'  => 'Edit Pasien',
            'pasien' => Pasien::findOrFail($id),
            'poli'   => Poliklinik::all()
        ]);
    }

    public function update(Request $r, $id)
    {
        // update penghitungan umur jika tanggal lahir diubah
        $tgl = Carbon::parse($r->tanggal_lahir);
        $now = Carbon::now();

        $r['umur_tahun'] = $tgl->diffInYears($now);
        $r['umur_bulan'] = $tgl->copy()->addYears($r['umur_tahun'])->diffInMonths($now);
        $r['umur_hari']  = $tgl->copy()
                              ->addYears($r['umur_tahun'])
                              ->addMonths($r['umur_bulan'])
                              ->diffInDays($now);

        Pasien::findOrFail($id)->update($r->all());

        return redirect()->route('pasien.index')->with('success', 'Data pasien diupdate');
    }

    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
        return back()->with('success', 'Data pasien dihapus');
    }
}
