<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;


class PendaftaranController extends Controller {


public function create() {
$poliklinik = Poliklinik::all();
return view('pendaftaran.create', compact('poliklinik'));
}


public function store(Request $request) {
$request->validate([
'pasien_id' => 'required',
'poliklinik_id' => 'required'
]);


$poli = Poliklinik::find($request->poliklinik_id);


$last = Pendaftaran::where('poliklinik_id', $poli->id)
->orderBy('id','DESC')->first();


$newNumber = 1;
if ($last) {
$angka = (int) substr($last->nomor_antrian, 2);
$newNumber = $angka + 1;
}


$format = $poli->kode . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);


Pendaftaran::create([
'pasien_id' => $request->pasien_id,
'poliklinik_id' => $request->poliklinik_id,
'nomor_antrian' => $format,
'status' => 'Menunggu'
]);


return redirect()->back()->with('success','Pendaftaran berhasil!');
}
}
?>