<?php

namespace App\Http\Controllers;

use App\Services\GeminiAnalysisService;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    protected $geminiAnalysis;

    public function __construct(GeminiAnalysisService $geminiAnalysis)
    {
        $this->geminiAnalysis = $geminiAnalysis;
    }

    public function index()
    {
        return view('analysis.index');
    }

    public function roast()
    {
        $result = $this->geminiAnalysis->roastFinances(auth()->id());

        if (!$result) {
            return response()->json(['error' => 'Gagal menganalisis keuangan. Tambahkan lebih banyak transaksi atau pastikan API key sudah diset.'], 422);
        }

        return response()->json(['result' => $result]);
    }

    public function tips()
    {
        $result = $this->geminiAnalysis->getSavingTips(auth()->id());

        if (!$result) {
            return response()->json(['error' => 'Gagal mendapatkan tips. Tambahkan transaksi bulan ini terlebih dahulu.'], 422);
        }

        return response()->json(['result' => $result]);
    }
}
