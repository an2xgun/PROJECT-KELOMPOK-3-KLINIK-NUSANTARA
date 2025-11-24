<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterTindakan;

class MasterTindakanController extends Controller
{
    public function index(){ $data = MasterTindakan::all(); return view('tindakan.index', compact('data')); }
    public function create(){ return view('tindakan.create'); }
    public function store(Request $r){ $r->validate(['nama'=>'required','harga'=>'required|numeric']); MasterTindakan::create($r->all()); return redirect()->route('tindakan.index')->with('success','Ditambahkan'); }
    public function edit($id){ $d = MasterTindakan::findOrFail($id); return view('tindakan.edit', compact('d')); }
    public function update(Request $r,$id){ $d = MasterTindakan::findOrFail($id); $r->validate(['nama'=>'required','harga'=>'required|numeric']); $d->update($r->all()); return redirect()->route('tindakan.index')->with('success','Diupdate'); }
    public function destroy($id){ MasterTindakan::destroy($id); return back()->with('success','Dihapus'); }
}
