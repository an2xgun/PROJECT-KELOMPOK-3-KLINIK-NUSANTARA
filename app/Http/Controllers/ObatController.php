<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\JenisObat;

class ObatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,apoteker');
    }

    public function index(Request $r)
    {
        $query = Obat::with('jenis')->orderBy('id', 'DESC');

        if ($r->q) {
            $query->where('nama', 'like', '%'.$r->q.'%')
                  ->orWhere('kodeobat', 'like', '%'.$r->q.'%');
        }

        $data = $query->paginate(15);
        return view('gudang_obat.index', compact('data'));
    }

    public function create()
    {
        $jenis = JenisObat::all();
        return view('gudang_obat.create', compact('jenis'));
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'kodeobat' => 'nullable|unique:obats',
            'nama' => 'required|string',
            'id_jenis' => 'required|exists:jenis,id',
            'dosis' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric',
            'expired' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($r->hasFile('photo')) {
            $file = $r->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/obat'), $filename);
            $validated['photo'] = 'uploads/obat/' . $filename;
        }

        Obat::create($validated);
        return redirect()->route('gudang_obat.index')->with('success', 'Obat ditambahkan');
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $jenis = JenisObat::all();
        return view('gudang_obat.edit', compact('obat', 'jenis'));
    }

    public function update(Request $r, $id)
    {
        $user = $r->user();

        // If apoteker is editing, only allow stok and harga to be updated (prevent missing disabled fields)
        if ($user && $user->role === 'apoteker') {
            $validated = $r->validate([
                'stok' => 'required|integer|min:0',
                'harga' => 'required|numeric'
            ]);
            $obat = Obat::findOrFail($id);
            $obat->update($validated);
            return redirect()->route('gudang_obat.index')->with('success', 'Stok & harga obat diperbarui');
        }

        // Admin or other roles: full update
        $validated = $r->validate([
            'kodeobat' => 'nullable|unique:obats,kodeobat,'.$id,
            'nama' => 'required|string',
            'id_jenis' => 'required|exists:jenis,id',
            'dosis' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric',
            'expired' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $obat = Obat::findOrFail($id);

        if ($r->hasFile('photo')) {
            $file = $r->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/obat'), $filename);
            $validated['photo'] = 'uploads/obat/' . $filename;
        }

        $obat->update($validated);
        return redirect()->route('gudang_obat.index')->with('success', 'Obat diupdate');
    }

    public function destroy($id)
    {
        Obat::findOrFail($id)->delete();
        return back()->with('success', 'Obat dihapus');
    }
}
