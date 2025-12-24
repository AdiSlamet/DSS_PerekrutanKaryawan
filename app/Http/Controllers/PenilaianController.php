<?php

namespace App\Http\Controllers;

use App\Models\penilaian;
use App\Models\detail_penilaian;
use App\Models\kandidat;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode = $request->input('periode');
        $query = penilaian::with(['kandidat', 'detailPenilaian.kriteria', 'detailPenilaian.subKriteria']);

        if ($periode){
            $query->where('periode', $periode . '-01');
        }

        $penilaian = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $penilaian
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
            'kandidat_id' => 'required|exists:kandidats,id',
            'sub_kriteria_id' => 'required|array',
            'sub_kriteria_id.*.kriteria_id' => 'required|exists:kriterias,id',
            'sub_kriteria_id.*.sub_kriteria_id' => 'required|exists:sub_kriterias,id'
        ]);

        // Cek apakah kandidat sudah di nilai
        $existingPenilaian = penilaian::where('kandidat_id', $request->kandidat_id)->first();
        if ($existingPenilaian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kandidat sudah dinilai'
            ], 400);
        }

        $kandidat = kandidat::find($request->kandidat_id);
        $periode = $kandidat->created_at->format('Y-m') . '-01';

        $penilaian = penilaian::create([
            'kandidat_id' => $request->kandidat_id,
            'periode' => $periode
        ]);

        foreach ($request->sub_kriteria_id as $dtail) {
            detail_penilaian::create([
                'penilaian_id' => $penilaian->id,
                'kriteria_id' => $dtail['kriteria_id'],
                'sub_kriteria_id' => $dtail['sub_kriteria_id']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $penilaian,
            'message' => 'Penilaian berhasil ditambahkan'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penilaian = penilaian::with(['kandidat', 'detailPenilaian.kriteria', 'detailPenilaian.subKriteria'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $penilaian
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penilaian = penilaian::findOrFail($id);
        $penilaian->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Penilaian berhasil dihapus'
        ]);
    }
}
