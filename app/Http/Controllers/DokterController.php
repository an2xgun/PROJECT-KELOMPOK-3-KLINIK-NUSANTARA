<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokter = Dokter::all();
        return view('master.dokter.index', compact('dokter'));
    }

    public function create()
    {
        return view('master.dokter.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama' => 'required',
            'spesialis' => 'required',
        ]);

        Dokter::create($r->all());
        return redirect()->route('master.dokter.index')->with('success', 'Dokter ditambahkan');
    }

    public function edit($id)
    {
        $d = Dokter::findOrFail($id);
        return view('master.dokter.edit', compact('d'));
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'nama' => 'required',
            'spesialis' => 'required',
        ]);

        $d = Dokter::findOrFail($id);
        $d->update($r->all());

        return redirect()->route('master.dokter.index')->with('success', 'Dokter diupdate');
    }

    public function destroy($id)
    {
        Dokter::destroy($id);
        return back()->with('success', 'Dokter dihapus');
    }
}
