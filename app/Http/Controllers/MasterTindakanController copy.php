<?php

namespace App\Http\Controllers;

use App\Models\MasterTindakan;
use Illuminate\Http\Request;

class MasterTindakanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $treatments = Tindakan::when($q, function($query, $q) {
                $query->where('code', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%")
                      ->orWhere('tarif', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('master.data_tindakan.index', compact('treatments', 'q'));
    }

    public function create()
    {
        return view('master.data_tindakan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:treatments,code',
            'name' => 'required|string|max:255',
            'tarif' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Tindakan::create($data);

        return redirect()->route('master.data_tindakan.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function show(Tindakan $treatments)
    {
        return view('master.data_tindakan.show', compact('tindakan'));
    }

    public function edit(Tindakan $treatments)
    {
        return view('master.data_tindakan.edit', compact('tindakan'));
    }

    public function update(Request $request, Tindakan $treatments)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:tindakans,code,' . $treatments->id,
            'name' => 'required|string|max:255',
            'tarif' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $tindakan->update($data);

        return redirect()->route('master.data_tindakan.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    public function destroy(Tindakan $tindakan)
    {
        $tindakan->delete();
        return redirect()->route('master.data_tindakan.index')->with('success', 'Tindakan berhasil dihapus.');
    }
}
