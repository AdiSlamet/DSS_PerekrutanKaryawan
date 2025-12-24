<?php

namespace App\Http\Controllers;

use App\Models\sub_kriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getByKriteria($kriteria_id)
    {
        $subKriteria = sub_kriteria::where('kriteria_id', $kriteria_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $subKriteria
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
            'kriteria_id' => 'required|integer|exists:kriterias,id',
            'nama' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);
        $subKriteria = sub_kriteria::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $subKriteria,
            'message' => 'Sub Kriteria berhasil ditambahkan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subKriteria = sub_kriteria::with('kriteria')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $subKriteria
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sub_kriteria $sub_kriteria)
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
            'nilai' => 'required|numeric',
        ]);

        $subKriteria = sub_kriteria::findOrFail($id);
        $subKriteria->update($request->only(['nama', 'nilai']));

        return response()->json([
            'status' => 'success',
            'data' => $subKriteria,
            'message' => 'Sub Kriteria berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $subKriteria = sub_kriteria::findOrFail($id);
        $subKriteria->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Kriteria berhasil dihapus'
        ]);
    }
}
