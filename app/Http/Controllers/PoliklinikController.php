<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        $poliklinik = Poliklinik::all();
        return view('poliklinik.index', compact('poliklinik'));
    }

    public function create()
    {
        return view('poliklinik.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_poli' => 'required',
            'kode' => 'required',
        ]);

        Poliklinik::create($r->all());

        return redirect()->route('poliklinik.index')->with('success', 'Poliklinik ditambahkan');
    }

    public function edit($id)
    {
        $poliklinik = Poliklinik::findOrFail($id);
        return view('poliklinik.edit', compact('poliklinik'));
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'nama_poli' => 'required',
            'kode' => 'required',
        ]);

        $poliklinik = Poliklinik::findOrFail($id);
        $poliklinik->update($r->all());

        return redirect()->route('poliklinik.index')->with('success', 'Poliklinik diupdate');
    }

    public function destroy($id)
    {
        Poliklinik::destroy($id);
        return back()->with('success', 'Poliklinik dihapus');
    }
}
