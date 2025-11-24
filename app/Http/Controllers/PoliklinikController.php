<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poliklinik;

class PoliklinikController extends Controller
{
    public function index(){ $data = Poliklinik::all(); return view('poliklinik.index', compact('data')); }
    public function create(){ return view('poliklinik.create'); }
    public function store(Request $r){ $r->validate(['kode'=>'required|unique:polikliniks','nama'=>'required']); Poliklinik::create($r->all()); return redirect()->route('poliklinik.index')->with('success','Poliklinik ditambahkan'); }
    public function edit($id){ $d = Poliklinik::findOrFail($id); return view('poliklinik.edit', compact('d')); }
    public function update(Request $r,$id){ $p = Poliklinik::findOrFail($id); $r->validate(['kode'=>"required|unique:polikliniks,kode,$id",'nama'=>'required']); $p->update($r->all()); return redirect()->route('poliklinik.index')->with('success','Diupdate'); }
    public function destroy($id){ Poliklinik::destroy($id); return back()->with('success','Dihapus'); }
}
