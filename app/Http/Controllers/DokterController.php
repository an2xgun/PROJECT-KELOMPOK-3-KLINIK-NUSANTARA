<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Dokter;

class DokterController extends Controller
{
    public function index(){ $data = Dokter::all(); return view('dokter.index', compact('data')); }
    public function create(){ return view('dokter.create'); }
    public function store(Request $r){ $r->validate(['nama'=>'required']); Dokter::create($r->all()); return redirect()->route('dokter.index')->with('success','Ditambahkan'); }
    public function edit($id){ $d = Dokter::findOrFail($id); return view('dokter.edit', compact('d')); }
    public function update(Request $r,$id){ $d = Dokter::findOrFail($id); $r->validate(['nama'=>'required']); $d->update($r->all()); return redirect()->route('dokter.index')->with('success','Diupdate'); }
    public function destroy($id){ Dokter::destroy($id); return back()->with('success','Dihapus'); }
}
