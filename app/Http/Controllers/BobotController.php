<?php

namespace App\Http\Controllers;

use App\Models\bobot;
use Illuminate\Http\Request;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bobot = bobot::with('kriteria')->get();

        return response()->json([
            'status' => 'success',
            'data' => $bobot
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
            'bobot' => 'required|numeric',
        ]);

        $bobot = bobot::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $bobot,
            'message' => 'Bobot berhasil ditambahkan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bobot = bobot::with('kriteria')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $bobot
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(bobot $bobot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bobot' => 'required|numeric|min:0',
        ]);

        $bobot= bobot::findOrFail($id);
        $bobot->update($request->only(['bobot']));

        return response()->json([
            'status' => 'success',
            'data' => $bobot,
            'message' => 'Bobot berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bobot = bobot::findOrFail($id);
        $bobot->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bobot berhasil dihapus'
        ]);
    }
}
