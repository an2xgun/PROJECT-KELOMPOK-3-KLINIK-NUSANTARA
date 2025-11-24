<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterDiagnosa;

class MasterDiagnosaController extends Controller
{
    public function index(){ $data = MasterDiagnosa::all(); return view('diagnosa.index', compact('data')); }
    public function create(){ return view('diagnosa.create'); }
    public function store(Request $r){ $r->validate(['nama'=>'required']); MasterDiagnosa::create($r->all()); return redirect()->route('diagnosa.index')->with('success','Ditambahkan'); }
    public function edit($id){ $d = MasterDiagnosa::findOrFail($id); return view('diagnosa.edit', compact('d')); }
    public function update(Request $r,$id){ $d = MasterDiagnosa::findOrFail($id); $r->validate(['nama'=>'required']); $d->update($r->all()); return redirect()->route('diagnosa.index')->with('success','Diupdate'); }
    public function destroy($id){ MasterDiagnosa::destroy($id); return back()->with('success','Dihapus'); }
}
