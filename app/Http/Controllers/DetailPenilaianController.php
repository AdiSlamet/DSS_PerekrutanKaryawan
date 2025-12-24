<?php

namespace App\Http\Controllers;

use App\Models\detail_penilaian;
use Illuminate\Http\Request;
use App\Models\penilaian;
use App\Models\sub_kriteria;
use App\Models\bobot;

class DetailPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode = $request->input('periode');
        $query = penilaian::with(['kandidat', 'detailPenilaian']);

        if ($periode){
            $query->where('periode', $periode . '-01');
        }

        $hasil = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $hasil
        ]);
    }

    public function statistik(Request $request)
    {
        $periode = $request->input('periode');

        $baseQuery = penilaian::query();

        if ($periode) {
            $baseQuery->where('periode', $periode . '-01');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kandidat' => (clone $baseQuery)->count(),

                'skor_tertinggi' => round((clone $baseQuery)->max('total_skor') ?? 0, 2),
                'skor_terendah'  => round((clone $baseQuery)->min('total_skor') ?? 0, 2),
                'rata_skor'      => round((clone $baseQuery)->avg('total_skor') ?? 0, 2),

                'direkomendasikan' => (clone $baseQuery)
                    ->where('total_skor', '>=', 4.0)
                    ->count(),

                'memenuhi_syarat' => (clone $baseQuery)
                    ->whereBetween('total_skor', [3.0, 3.99])
                    ->count(),

                'perlu_dipertimbangkan' => (clone $baseQuery)
                    ->where('total_skor', '<', 3.0)
                    ->count(),
            ]
        ]);
    }


    public function ranking(Request $request)
    {
        $periode = $request->input('periode');
        $query = penilaian::with(['kandidat']);

        if ($periode){
            $query->where('periode', $periode . '-01');
        }

        $ranking = $query->orderBy('total_skor', 'desc')
                        ->get()
                        ->map(function ($item, $index){
                            return [
                                'rank' => $index + 1,
                                'nama_kandidat' => $item->kandidat->nama,
                                'total_skor' => $item->total_skor,
                                'klasifikasi' => $item->klasifikasi
                            ];
                        });

        return response()->json([
            'status' => 'success',
            'data' => $ranking
        ]);
    }

    public function distribusi(Request $request)
    {
        $periode = $request->input('periode');
        $baseQuery = penilaian::query();

        if ($periode) {
            $baseQuery->where('periode', $periode . '-01');
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'sangat_baik' => (clone $baseQuery)
                    ->whereBetween('total_skor', [4.0, 5.0])
                    ->count(),

                'baik' => (clone $baseQuery)
                    ->whereBetween('total_skor', [3.0, 3.99])
                    ->count(),

                'cukup' => (clone $baseQuery)
                    ->whereBetween('total_skor', [2.0, 2.99])
                    ->count(),

                'kurang' => (clone $baseQuery)
                    ->where('total_skor', '<', 2.0)
                    ->count(),
            ]
        ]);
    }


    public function hitungSMART($penilaian_id)
    {
        $penilaian = Penilaian::with(['detailPenilaian.kriteria', 'detailPenilaian.subKriteria'])->findOrFail($penilaian_id);
        
        // Ambil semua bobot
        $bobotData = Bobot::with('kriteria')->get();
        
        $totalBobot = $bobotData->sum('bobot');
        $totalSkor = 0;
        
        foreach ($penilaian->detailPenilaian as $detail) {
            $kriteria = $detail->kriteria;
            $subKriteria = $detail->subKriteria;
            $bobot = $bobotData->where('kriteria_id', $kriteria->id)->first();
            
            if (!$bobot) {
                continue;
            }
            
            // Ambil semua sub kriteria untuk kriteria ini
            $allSub = sub_kriteria::where('kriteria_id', $kriteria->id)->get();
            $maxNilai = $allSub->max('nilai');
            $minNilai = $allSub->min('nilai');
            
            // Normalisasi
            if ($kriteria->jenis == 'benefit') {
                $normalized = ($subKriteria->nilai - $minNilai) / ($maxNilai - $minNilai);
            } else { // cost
                $normalized = ($maxNilai - $subKriteria->nilai) / ($maxNilai - $minNilai);
            }
            
            // Hitung nilai terbobot
            $weighted = $normalized * $bobot->bobot;
            
            // Update detail penilaian
            $detail->update([
                'nilai_normalisasi' => $normalized,
                'nilai_terbobot' => $weighted
            ]);
            
            $totalSkor += $weighted;
        }
        
        // Skor maksimal mungkin (normalisasi)
        $totalSkorNormalized = $totalBobot > 0 ? $totalSkor / $totalBobot * 5 : 0;
        
        // Klasifikasi
        $klasifikasi = '';
        if ($totalSkorNormalized >= 4.0) {
            $klasifikasi = 'direkomendasikan';
        } elseif ($totalSkorNormalized >= 3.0) {
            $klasifikasi = 'memenuhi syarat';
        } else {
            $klasifikasi = 'perlu dipertimbangkan';
        }
        
        $penilaian->update([
            'total_skor' => $totalSkorNormalized,
            'klasifikasi' => $klasifikasi
        ]);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_skor' => round($totalSkorNormalized, 2),
                'klasifikasi' => $klasifikasi,
                'detail_penilaian' => $penilaian->detailPenilaian
            ],
            'message' => 'Perhitungan SMART berhasil'
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(detail_penilaian $detail_penilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detail_penilaian $detail_penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detail_penilaian $detail_penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detail_penilaian $detail_penilaian)
    {
        //
    }
}
