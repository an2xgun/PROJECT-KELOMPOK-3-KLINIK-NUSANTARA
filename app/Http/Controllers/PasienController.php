<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $r)
    {
        $query = Pasien::orderBy('id', 'DESC');

        if ($r->q) {
            $query->where('nama', 'like', '%'.$r->q.'%')
                  ->orWhere('kodepasien', 'like', '%'.$r->q.'%')
                  ->orWhere('no_rm', 'like', '%'.$r->q.'%');
        }

        $data = $query->paginate(15);

        return view('pasien.index', compact('data'));
    }

    public function create()
    {
        // Provide next No RM for the form (peek without consuming sequence)
        $nextNoRm = Pasien::peekNextNoRm();

        return view('pasien.create', [
            'title' => 'Data Pasien Baru',
            'no_rm' => $nextNoRm
        ]);
    }

    public function store(Request $r)
    {
        $validated = $r->validate([
            'no_rm' => 'required|unique:pasiens,no_rm',
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'lahir' => 'required|date',
            'nik' => 'nullable|string',
            'kelamin' => 'required|in:laki-laki,perempuan',
            'telepon' => 'required|string',
            'agama' => 'required|string',
            'pendidikan' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        // Ensure no_rm is set (in case client didn't provide it)
        if (empty($validated['no_rm'])) {
            $validated['no_rm'] = Pasien::generateNextNoRm();
        }

        Pasien::create($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('pasien.edit', [
            'title'  => 'Edit Pasien',
            'pasien' => Pasien::findOrFail($id),
        ]);
    }

    public function update(Request $r, $id)
    {
        $validated = $r->validate([
            'no_rm' => 'required|unique:pasiens,no_rm,'.$id,
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'lahir' => 'required|date',
            'nik' => 'nullable|string',
            'kelamin' => 'required|in:laki-laki,perempuan',
            'telepon' => 'required|string',
            'agama' => 'required|string',
            'pendidikan' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
        ]);

        Pasien::findOrFail($id)->update($validated);

        return redirect()->route('pasien.index')->with('success', 'Data pasien diupdate');
    }

    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
        return back()->with('success', 'Data pasien dihapus');
    }

    // AJAX Search untuk Pendaftaran — accept either no_rm or legacy kodepasien
    public function getByNoRM($identifier)
    {
        $pasien = Pasien::where('no_rm', $identifier)
            ->orWhere('kodepasien', $identifier)
            ->first();

        if (!$pasien) return response()->json(null, 404);
        return response()->json($pasien);
    }
}
