<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;
use App\Models\Dokter;

class JadwalPoliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){ $data = JadwalPoli::with(['poliklinik','dokter'])->get(); return view('jadwal.index', compact('data')); }
    public function create(){ $poliklinik = Poliklinik::all(); $dokter = Dokter::all(); return view('jadwal.create', compact('poliklinik','dokter')); }
    public function store(Request $r){ $r->validate(['poliklinik_id'=>'required','dokter_id'=>'required','hari'=>'required','jam_mulai'=>'required','jam_selesai'=>'required']); JadwalPoli::create($r->all()); return redirect()->route('jadwal.index')->with('success','Ditambahkan'); }
    public function edit($id){ $d = JadwalPoli::findOrFail($id); $poliklinik = Poliklinik::all(); $dokter = Dokter::all(); return view('jadwal.edit', compact('d','poliklinik','dokter')); }
    public function update(Request $r,$id){ $d = JadwalPoli::findOrFail($id); $r->validate(['poliklinik_id'=>'required','dokter_id'=>'required','hari'=>'required','jam_mulai'=>'required','jam_selesai'=>'required']); $d->update($r->all()); return redirect()->route('jadwal.index')->with('success','Diupdate'); }
    public function destroy($id){ JadwalPoli::destroy($id); return back()->with('success','Dihapus'); }
}
