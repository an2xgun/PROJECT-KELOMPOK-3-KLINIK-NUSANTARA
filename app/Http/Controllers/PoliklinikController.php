<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poliklinik;
use App\Models\Pendaftaran;

class PoliklinikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Poliklinik::all();
        return view('poliklinik.index', compact('data'));
    }

    public function create()
    {
        return view('poliklinik.create');
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'name' => 'required|unique:polikliniks'
        ]);

        Poliklinik::create($validated);
        return redirect()->route('poliklinik.index')->with('success','Poliklinik ditambahkan');
    }

    public function edit($id)
    {
        $poli = Poliklinik::findOrFail($id);
        return view('poliklinik.edit', compact('poli'));
    }

    public function update(Request $r, $id)
    {
        $validated = $r->validate([
            'name' => 'required|unique:polikliniks,name,'.$id
        ]);

        Poliklinik::findOrFail($id)->update($validated);
        return redirect()->route('poliklinik.index')->with('success','Poliklinik diupdate');
    }

    public function destroy($id)
    {
        Poliklinik::destroy($id);
        return back()->with('success','Poliklinik dihapus');
    }

    public function umum()
    {
        $pendaftaran = Pendaftaran::where('poliklinik_id', 1)->with('pasien')->paginate(15);
        return view('poliklinik.poli_umum', compact('pendaftaran'));
    }

    public function gigi()
    {
        $pendaftaran = Pendaftaran::where('poliklinik_id', 2)->with('pasien')->paginate(15);
        return view('poliklinik.poli_gigi', compact('pendaftaran'));
    }

    public function kandungan()
    {
        $pendaftaran = Pendaftaran::where('poliklinik_id', 3)->with('pasien')->paginate(15);
        return view('poliklinik.poli_kandungan', compact('pendaftaran'));
    }
}
