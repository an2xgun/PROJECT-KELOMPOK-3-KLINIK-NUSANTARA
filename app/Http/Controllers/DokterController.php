<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\Poliklinik;

class DokterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Dokter::with('poliklinik')->paginate(15);
        return view('master.dokter.index', compact('data'));
    }

    public function create()
    {
        $polikliniks = Poliklinik::all();
        return view('master.dokter.create', compact('polikliniks'));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'poliklinik_id' => 'required|exists:polikliniks,id',
            'telepon' => 'required|string',
            'jadwalpraktek' => 'required|string'
        ]);

        Dokter::create($validated);
        return redirect()->route('master.jadwal_dokter')->with('success','Dokter ditambahkan');
    }

    public function edit($id)
    {
        $dokter = Dokter::findOrFail($id);
        $polikliniks = Poliklinik::all();
        return view('master.dokter.edit', compact('dokter', 'polikliniks'));
    }

    public function update(Request $r, $id)
    {
        $validated = $r->validate([
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'poliklinik_id' => 'required|exists:polikliniks,id',
            'telepon' => 'required|string',
            'jadwalpraktek' => 'required|string'
        ]);

        Dokter::findOrFail($id)->update($validated);
        return redirect()->route('master.jadwal_dokter')->with('success','Dokter diupdate');
    }

    public function destroy($id)
    {
        Dokter::destroy($id);
        return back()->with('success','Dokter dihapus');
    }
}
