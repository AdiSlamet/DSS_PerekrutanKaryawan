<?php

namespace App\Http\Controllers;

use App\Models\kandidat;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Penilaian;

class KandidatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode = $request->input('periode');
        $query = kandidat::query();

        if ($periode){
            list($year, $month) = explode('-', $periode);
            $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
        }

        $kandidat = $query->get()->map(function ($item){
            return[
                'id' => $item->id,
                'nama' => $item->nama,
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d'),
                'periode' => $item->created_at->format('Y-m')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $kandidat
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kandidat = kandidat::create([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $kandidat,
            'massage' => 'Kandidat berhasil ditambahkan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kandidat = kandidat::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $kandidat
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kandidat $kandidat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kandidat = kandidat::findOrFail($id);
        $kandidat->update([
            'nama' => $request->nama,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $kandidat,
            'message' => 'Kandidat berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kandidat = kandidat::findOrFail($id);
        $kandidat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kandidat berhasil dihapus'
        ]);
    }
}
