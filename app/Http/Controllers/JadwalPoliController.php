<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;
use App\Models\Dokter;

class JadwalPoliController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPoli::with(['poliklinik', 'dokter'])->get();
        return view('master.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $poliklinik = Poliklinik::all();
        $dokter = Dokter::all();

        return view('master.jadwal.create', compact('poliklinik', 'dokter'));
    }

 public function store(Request $request)
{
    $request->validate([

        'poli_id'=>'required|exists:polikliniks,id',
        'dokter_id'=>'required|exists:dokters,id',
        'hari'=>'required',
        'jam_mulai'=>'required',
        'jam_selesai'=>'required',
    ]);

    JadwalPoli::create($request->all());

    return redirect()->route('master.jadwal.index')->with('success','Jadwal berhasil ditambahkan');
}

    public function edit($id)
    {
        $d = JadwalPoli::findOrFail($id);
        $poliklinik = Poliklinik::all();
        $dokter = Dokter::all();

        return view('master.jadwal.edit', compact('d', 'poliklinik', 'dokter'));
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'poli_id' => 'required',
            'dokter_id' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $d = JadwalPoli::findOrFail($id);
        $d->update($r->all());

        return redirect()->route('master.jadwal.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        JadwalPoli::destroy($id);
        return back()->with('success', 'Jadwal berhasil dihapus');
    }

    // ===== AJAX =====
    public function getDokterByPoli($poli_id)
    {
        $jadwals = JadwalPoli::where('poli_id', $poli_id)
            ->with('dokter')
            ->get();

        return response()->json($jadwals);
    }
}
