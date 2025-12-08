<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterDiagnosa;

class MasterDiagnosaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){ $data = MasterDiagnosa::all(); return view('diagnosa.index', compact('data')); }
    public function create(){ return view('diagnosa.create'); }
    public function store(Request $r){
        $r->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['nullable','string','max:20','regex:/^[A-Z][0-9]{2}(?:\.[0-9A-Za-z]+)?$/i']
        ]);

        $data = $r->only(['kode','nama']);
        if (!empty($data['kode'])) $data['kode'] = strtoupper($data['kode']);

        MasterDiagnosa::create($data);
        return redirect()->route('diagnosa.index')->with('success','Ditambahkan');
    }
    public function edit($id){ $d = MasterDiagnosa::findOrFail($id); return view('diagnosa.edit', compact('d')); }
    public function update(Request $r,$id){
        $d = MasterDiagnosa::findOrFail($id);
        $r->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['nullable','string','max:20','regex:/^[A-Z][0-9]{2}(?:\.[0-9A-Za-z]+)?$/i']
        ]);

        $data = $r->only(['kode','nama']);
        if (!empty($data['kode'])) $data['kode'] = strtoupper($data['kode']);

        $d->update($data);
        return redirect()->route('diagnosa.index')->with('success','Diupdate');
    }
    public function destroy($id){ MasterDiagnosa::destroy($id); return back()->with('success','Dihapus'); }

    /**
     * API: create diagnosa (JSON)
     * Allowed for roles: admin, dokter
     */
    public function apiStore(Request $r)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dokter'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $r->validate([
            'nama' => 'required|string|max:255',
            'kode' => ['nullable','string','max:20','regex:/^[A-Z][0-9]{2}(?:\.[0-9A-Za-z]+)?$/i']
        ]);

        $data = $r->only(['kode','nama']);
        if (!empty($data['kode'])) $data['kode'] = strtoupper($data['kode']);

        $d = MasterDiagnosa::create($data);

        return response()->json($d, 201);
    }

    /**
     * API: delete diagnosa (JSON)
     * Allowed for roles: admin, dokter
     */
    public function apiDestroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'dokter'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $d = MasterDiagnosa::find($id);
        if (!$d) {
            return response()->json(['error' => 'Not found'], 404);
        }

        try {
            $d->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete'], 500);
        }
    }
}
