<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kandidat;
use App\Models\Penilaian;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode');

        $year = null;
        $month = null;

        if ($periode) {
            [$year, $month] = explode('-', $periode);
        }

        // Total kandidat
        $totalKandidat = Kandidat::when($periode, function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        })->count();

        // Kandidat sudah dinilai
        $sudahDinilai = Penilaian::whereHas('kandidat', function ($q) use ($periode, $year, $month) {
            if ($periode) {
                $q->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
            }
        })->count();

        $belumDinilai = $totalKandidat - $sudahDinilai;

        // Rata-rata skor (ikut filter periode)
        $rataSkor = Penilaian::when($periode, function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        })->avg('total_skor') ?? 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kandidat' => $totalKandidat,
                'sudah_dinilai' => $sudahDinilai,
                'belum_dinilai' => $belumDinilai,
                'rata_skor' => round($rataSkor, 2),
                'periode' => $periode
            ]
        ]);
    }

}
