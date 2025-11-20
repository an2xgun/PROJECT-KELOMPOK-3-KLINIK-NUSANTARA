<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    /**
     * 🔹 Tampilkan semua pasien (Data Master)
     */
    public function index()
    {
        $pasien = Pasien::all();
        return view('data_master', [
            'title' => 'Data Master Pasien',
            'pasien' => $pasien
        ]);
    }

    /**
     * 🔹 Form Pendaftaran Pasien Baru
     */
    public function create()
    {
        return view('pendaftaran.pasien_baru', [
            'title' => 'Pendaftaran Pasien Baru'
        ]);
    }

    /**
     * 🔹 Simpan Pasien Baru ke Database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik',
            'no_rm' => 'required|unique:pasiens,no_rm',
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        Pasien::create($validated);

        return redirect()->route('data.master')->with('success', '✅ Data pasien baru berhasil disimpan!');
    }

    /**
     * 🔹 Tampilkan Form Pencarian Pasien Lama
     */
    public function searchForm()
    {
        return view('pendaftaran.pasien_lama', [
            'title' => 'Cari Pasien Lama',
            'hasil' => null
        ]);
    }

    /**
     * 🔹 Lakukan Pencarian Pasien Lama
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $hasil = Pasien::where('nik', 'like', "%$keyword%")
            ->orWhere('nama', 'like', "%$keyword%")
            ->orWhere('no_rm', 'like', "%$keyword%")
            ->get();

        if ($hasil->isEmpty()) {
            // Jika tidak ditemukan, arahkan ke form pasien baru
            return redirect()->route('pasien.baru')
                ->with('info', 'Pasien tidak ditemukan. Silakan daftarkan pasien baru.');
        }

        return view('pendaftaran.pasien_lama', [
            'title' => 'Hasil Pencarian Pasien Lama',
            'hasil' => $hasil
        ]);
    }

    /**
     * 🔹 Form Edit Pasien
     */
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('edit_pasien', [
            'title' => 'Edit Data Pasien',
            'pasien' => $pasien
        ]);
    }

    /**
     * 🔹 Simpan Perubahan Edit ke Database
     */
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|unique:pasiens,nik,' . $id,
            'no_rm' => 'required|unique:pasiens,no_rm,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required'
        ]);

        $pasien->update($validated);

        return redirect()->route('data.master')->with('success', '✅ Data pasien berhasil diperbarui!');
    }

    /**
     * 🔹 Hapus Data Pasien
     */
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('data.master')->with('success', '🗑️ Data pasien berhasil dihapus!');
    }
}
