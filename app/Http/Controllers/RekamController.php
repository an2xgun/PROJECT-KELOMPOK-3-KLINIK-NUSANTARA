<?php
<<<<<<< HEAD

namespace App\Http\Controllers;

use App\Models\Rekam;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validate = $request->validate([
            'id_player' => 'required',
            'layanan' => 'required',
            'keluhan' => 'required',
            'dokter' => 'required',
        	'g-recaptcha-response' => 'required|captcha'
        ],
        [
            'g-recaptcha-response' => [
                'required' => 'Please verify that you are not a robot.',
                'captcha' => 'Captcha error! try again later or contact site admin.',
            ],
        ],
        );

        $nomorAntrian = 1;
        $cekData = Rekam::whereDate('created_at', Carbon::today())->latest()->first();
        if ($cekData) {
            $nomorAntrian = $cekData->nomorantrian + 1;
        }

        $Rekam = Rekam::create([
            'nomorantrian' => "00" . $nomorAntrian,
            'id_pasien' => $validate['id_player'],
            'layanan' => $validate['layanan'],
            'keluhan' => $validate['keluhan'],
            'id_dokter' => $validate['dokter']
        ]);

        $latestrekam = Rekam::all()->last();
        $pasienid = $latestrekam->id_pasien;
        $pasientable = Pasien::where('id', $pasienid)->get();

        foreach ($pasientable as $row):

            return redirect('pasien-lama')->with([
                'addsuccess' => 'Data berhasil ditambahkan',
                'nomorAntrian' => "00" . $nomorAntrian,
                'nama' => $row->nama,
                'timestamps' => $Rekam->created_at->format('H:i:s'),
                'tanggaldaftar' => $Rekam->created_at->format('d-m-Y')
            ]);

        endforeach;
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
        $rekam = Rekam::find($id);
        return view('antrian-pasien-edit-form',[
            'rekam' => $rekam,
            'dokter' => Dokter::all()
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
        $validated = $request->validate([
            'layanan' => 'required',
            'keluhan' => 'required',
            'dokter' => 'required'
        ]);

        $rekam = Rekam::find($id);
        $rekam->layanan = $validated['layanan'];
        $rekam->keluhan = $validated['keluhan'];
        $rekam->id_dokter = $validated['dokter'];
        $rekam->save();

        return redirect('antrian-pasien-admin')->with('success', 'Data TerUpdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rekam = Rekam::find($id);
        $rekam->delete();
        return back()->with('success', 'Data Terhapus');
    }

    public function edits($id)
    {
        $rekam = Rekam::find($id);
        return view('rekam-pasien-edit-form',[
            'rekam' => $rekam
        ]);
=======
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekam;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class RekamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rekam = Rekam::with('pasien', 'dokter')->orderBy('id', 'DESC')->paginate(15);
        return view('rekam.index', compact('rekam'));
    }

    public function create($pendaftaran_id = null)
    {
        $pendaftaran = null;
        if ($pendaftaran_id) {
            $pendaftaran = Pendaftaran::with(['pasien','jadwalPoli.dokter'])->findOrFail($pendaftaran_id);
        }
        
        $dokters = Dokter::all();
        $obats = Obat::all();
        
        return view('rekam.create', compact('pendaftaran', 'dokters', 'obats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pasien' => 'required|exists:pasiens,id',
            'nomorantrian' => 'required|string',
            'layanan' => 'required|string',
            'keluhan' => 'required|string',
            'id_dokter' => 'required|exists:dokters,id',
            'diagnosa' => 'nullable|string',
            'darah' => 'nullable|string',
            'tinggi' => 'nullable|string',
            'berat' => 'nullable|string',
            'pinggang' => 'nullable|string',
        ]);

        $validated['tanggalperiksa'] = now()->toDateString();
        $validated['jadwal_kedatangan'] = now();

        $rekam = Rekam::create($validated);

        // Create prescription
        $prescription = Prescription::create([
            'rekam_id' => $rekam->id,
            'dokter_id' => $validated['id_dokter'],
            'status' => 'pending'
        ]);

        return redirect()->route('rekam.show', $rekam->id)->with('success', 'Rekam medis dibuat');
    }

    public function show($id)
    {
        $rekam = Rekam::with('pasien', 'dokter')->findOrFail($id);
        $prescription = $rekam->prescription;
        $obats = Obat::all();
        
        return view('rekam.show', compact('rekam', 'prescription', 'obats'));
    }

    public function edit($id)
    {
        $rekam = Rekam::findOrFail($id);
        $dokters = Dokter::all();
        return view('rekam.edit', compact('rekam', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'nullable|string',
            'darah' => 'nullable|string',
            'tinggi' => 'nullable|string',
            'berat' => 'nullable|string',
            'pinggang' => 'nullable|string',
        ]);

        Rekam::findOrFail($id)->update($validated);
        return redirect()->route('rekam.show', $id)->with('success', 'Rekam medis diupdate');
    }

    public function destroy($id)
    {
        Rekam::findOrFail($id)->delete();
        return back()->with('success', 'Rekam medis dihapus');
    }

    public function addObat($rekam_id, Request $request)
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric'
        ]);

        $rekam = Rekam::findOrFail($rekam_id);
        $prescription = $rekam->prescription;
        // Jika belum ada prescription untuk rekam ini, buat minimal agar bisa menambahkan obat
        if (!$prescription) {
            $prescription = Prescription::create([
                'rekam_id' => $rekam->id,
                'dokter_id' => $rekam->id_dokter ?? null,
                'status' => 'Pending'
            ]);
        }

        $harga_satuan = $validated['harga_satuan'];
        $subtotal = $harga_satuan * $validated['jumlah'];

        PrescriptionItem::create([
            'prescription_id' => $prescription->id,
            'obat_id' => $validated['obat_id'],
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $harga_satuan,
            'subtotal' => $subtotal
        ]);

        return back()->with('success', 'Obat ditambahkan ke resep');
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    }
}
