<?php
<<<<<<< HEAD

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jenis;
use App\Models\Obat;

use function PHPSTORM_META\map;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = obat::all();
        return view('obat-total-stok', [
            'obat' => $data
        ]);
    }

    public function form()
    {
        return view('obat-form', [
            'jenis' => Jenis::all()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaobat' => 'required',
            'image' => 'mimes:jpeg,jpg,png'
        ]);

        if ($image = $request->file('image'))
        {
            $destinationPath = 'image/';
            $profileImage = date('dmY') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $validated['image'] = "$profileImage";
        }

        Obat::create([
            'kodeobat' => $request->kode,
            'nama' => $validated['namaobat'],
            'id_jenis' => $request->jenis,
            'expired' => $request->expired,
            'stok' => $request->stok,
            'dosis' => $request->dosis,
            'photo' => $request->image,
            'harga' => $request->harga
        ]);

        return redirect('obat-total-stok')->with('success', 'Data berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('edit-obat-stok-form', [
            'data' => Obat::find($id),
            'jenis' => Jenis::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'namaobat' => 'required',
        ]);

        $validated = $request->all();
        
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $validated['image'] = "$profileImage";
        }else{
            unset($validated['image']);
        }

        $date = date('Y-m-d H:i:s');

        $data = Obat::find($id);
        $data->kodeobat = $request->kode;
        $data->nama = $validated['namaobat'];
        $data->id_jenis = $request->jenis;
        $data->expired = $request->expired;
        $data->dosis = $request->dosis;
        $data->created_at = $date;
        $data->harga = $request->harga;
        
        if($image != ''){
            $data->photo = $validated['image'];
        }

        $data->save();

        return redirect('obat-total-stok')->with('success', 'Data terupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obat = Obat::find($id);
        $obat->delete();

        return redirect('obat-total-stok')->with('success', 'Data berhasil dihapus');
    }

    public function editstok($id)
    {
        return view('edit-stok-obat-form', [
            'obat' => Obat::find($id)
        ]);
    }

    public function tambahstok(Request $request)
    {
        $validated = $request->validate([
            'id_obat' => 'required',
            'jumlahtambahan' => 'required'
        ]);

        $date = date('Y-m-d H:i:s');

        $obat = Obat::find($validated['id_obat']);
        $obat->stok = $obat->stok + $validated['jumlahtambahan'];
        $obat->expired = $request->expired;
        $obat->created_at = $date;
        $obat->save();

        return redirect('obat-total-stok')->with('success', 'Stok terupdate');
=======
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
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }
}
