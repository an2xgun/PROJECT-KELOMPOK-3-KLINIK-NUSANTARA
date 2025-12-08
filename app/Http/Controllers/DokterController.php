<?php
namespace App\Http\Controllers;
<<<<<<< HEAD

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\Jadwal;
=======
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\Poliklinik;

class DokterController extends Controller
{
<<<<<<< HEAD
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datadokter = Dokter::get();
        return view('dokter', compact('datadokter'));
=======
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = Dokter::with('poliklinik')->paginate(15);
        return view('master.dokter.index', compact('data'));
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
<<<<<<< HEAD
        $jadwalvariabel = Jadwal::all();
        return view('dokter-form', [
            'jadwalvariabel' => $jadwalvariabel,
            'poli' => Poli::all()
        ]);

        
=======
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
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'Nama' => 'required',
            'Alamat' => 'required',
            'Spesialis' => 'required',
            'Telepon' => 'required',
            'Jadwal' => 'required'

        ]);

        $Dokter= Dokter::create([
           
            'nama'=>ucwords(strtolower($request->Nama)),
            'alamat'=>$request->Alamat,            
            'id_poli'=>$request->Spesialis,            
            'telepon'=>$request->Telepon,
            'jadwalpraktek'=>$request->Jadwal

        ]);
     

        return redirect('/dokter')->with('success','Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dokter $dokter)
    {
        $dokter = Dokter::where('id', $dokter)->get();
        return view('dokter', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
<<<<<<< HEAD
        $jadwalvariabel = Jadwal::all();
        $dokter = Dokter::findOrfail($id);
        $poli = Poli::all();
        return view('dokter-form-edit', compact('dokter','jadwalvariabel', 'poli'));
=======
        $dokter = Dokter::findOrFail($id);
        $polikliniks = Poliklinik::all();
        return view('master.dokter.edit', compact('dokter', 'polikliniks'));
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dokter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
<<<<<<< HEAD
        // dd($request);
        $this->validate($request, [
            
            'Nama' => 'required',
            'Alamat' => 'required',
            'Spesialis' => 'required',
            'Telepon' => 'required',
            'Jadwal' => 'required'

        ]);

        $dokteredit = $request->all();
        $dokter = Dokter::find($id);

        $dokter->update([
          
            'nama'=>ucwords(strtolower($request->Nama)),
            'alamat'=>$request->Alamat,            
            'id_poli'=>$request->Spesialis,            
            'telepon'=>$request->Telepon,
            'jadwalpraktek'=>$request->Jadwal

        ]);

        return redirect()->route('dokter.index')->with('success', 'Data telah diubah');
=======
        $validated = $r->validate([
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
            'poliklinik_id' => 'required|exists:polikliniks,id',
            'telepon' => 'required|string',
            'jadwalpraktek' => 'required|string'
        ]);

        Dokter::findOrFail($id)->update($validated);
        return redirect()->route('master.jadwal_dokter')->with('success','Dokter diupdate');
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Dokter  $dokter
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dokter $dokter)
    {
<<<<<<< HEAD
        $dokter->delete();
        return redirect()->back();
=======
        Dokter::destroy($id);
        return back()->with('success','Dokter dihapus');
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }
}
