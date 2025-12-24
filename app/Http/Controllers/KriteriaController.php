<?php

namespace App\Http\Controllers;

use App\Models\kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriteria = kriteria::all();

        return response()->json([
            'status' => 'success',
            'data' => $kriteria
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
            'jenis' => 'required|in:benefit,cost',
        ]);

        $kriteria = kriteria::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $kriteria,
            'message' => 'Kriteria berhasil ditambahkan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kriteria = kriteria::with(['subkriteria', 'bobot'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $kriteria
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'jenis' => 'sometimes|required|in:benefit,cost',
        ]);

        // Cari kriteria berdasarkan ID
        $kriteria = Kriteria::findOrFail($id);
        
        // Update data
        $kriteria->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $kriteria,
            'message' => 'Kriteria berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kriteria = kriteria::findOrFail($id);
        $kriteria->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kriteria berhasil dihapus'
        ]);
    }
}
