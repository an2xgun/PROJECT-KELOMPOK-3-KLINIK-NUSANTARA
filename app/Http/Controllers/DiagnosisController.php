<?php

namespace App\Http\Controllers;

use App\Models\Diagnoses; // Pastikan ini sesuai
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $diagnoses = Diagnoses::when($q, function($query,$q){
                $query->where('code','like',"%{$q}%")
                      ->orWhere('name','like',"%{$q}%")
                      ->orWhere('icd10','like',"%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.data_diagnosa.index', compact('diagnoses', 'q'));
    }

    public function create()
    {
        return view('master.data_diagnosa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:diagnoses,code',
            'name' => 'required|string|max:255',
            'icd10' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Diagnoses::create($data);

        return redirect()->route('master.data_diagnosa.index')->with('success', 'Diagnosa berhasil ditambahkan.');
    }

    public function show(Diagnoses $diagnosis)
    {
        return view('master.data_diagnosa.show', compact('diagnosis'));
    }

    public function edit(Diagnoses $diagnosis)
    {
        return view('master.data_diagnosa.edit', compact('diagnosis'));
    }

    public function update(Request $request, Diagnoses $diagnosis)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:diagnoses,code,' . $diagnosis->id,
            'name' => 'required|string|max:255',
            'icd10' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $diagnosis->update($data);

        return redirect()->route('master.data_diagnosa.index')->with('success', 'Diagnosa berhasil diperbarui.');
    }

    public function destroy(Diagnoses $diagnosis)
    {
        $diagnosis->delete();
        return redirect()->route('master.data_diagnosa.index')->with('success', 'Diagnosa berhasil dihapus.');
    }
}
