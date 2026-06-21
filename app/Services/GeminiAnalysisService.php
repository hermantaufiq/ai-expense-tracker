<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GeminiAnalysisService
{
    public function roastFinances(int $userId): ?string
    {
        $apiKey = config('services.gemini.api_key');
        if (empty($apiKey)) return null;

        // Gather last 3 months of data
        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->where('transaction_date', '>=', Carbon::now()->subMonths(3))
            ->get();

        if ($transactions->isEmpty()) return null;

        $totalExpense = $transactions->filter(fn($t) => $t->category?->type === 'expense')->sum('amount');
        $totalIncome  = $transactions->filter(fn($t) => $t->category?->type === 'income')->sum('amount');

        $categoryBreakdown = $transactions
            ->filter(fn($t) => $t->category?->type === 'expense')
            ->groupBy(fn($t) => $t->category?->name ?? 'Lainnya')
            ->map(fn($group) => $group->sum('amount'))
            ->sortDesc()
            ->take(5)
            ->map(fn($total, $cat) => "{$cat}: Rp " . number_format($total, 0, ',', '.'))
            ->values()
            ->join(', ');

        $sisa = $totalIncome - $totalExpense;

        $prompt = <<<PROMPT
Kamu adalah konsultan keuangan yang jujur, cerdas, sekaligus lucu dan sedikit "roasting" tapi tetap membangun.

Berikut adalah data keuangan pengguna selama 3 bulan terakhir:
- Total Pemasukan: Rp {$totalIncome}
- Total Pengeluaran: Rp {$totalExpense}
- Sisa/Tabungan: Rp {$sisa}
- Kategori pengeluaran terbesar: {$categoryBreakdown}

Tugasmu:
1. Buat "roast" yang lucu tapi jujur tentang kebiasaan keuangan mereka (2-3 kalimat, pakai bahasa Indonesia gaul yang santai, pakai emoji)
2. Berikan 2-3 saran spesifik dan actionable untuk hemat berdasarkan data di atas (pakai bullet point dan Rp nyata)
3. Tutup dengan kalimat motivasi singkat yang menyemangati

Jangan terlalu panjang. Gunakan paragraf pendek dan mudah dibaca. Gunakan format yang rapi.
PROMPT;

        try {
            $response = Http::withoutVerifying()->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
                [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.8],
                ]
            );

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }
        } catch (\Exception $e) {
            Log::error('Gemini Analysis Error: ' . $e->getMessage());
        }

        return null;
    }

    public function getSavingTips(int $userId): ?string
    {
        $apiKey = config('services.gemini.api_key');
        if (empty($apiKey)) return null;

        $thisMonth = Carbon::now();
        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereYear('transaction_date', $thisMonth->year)
            ->whereMonth('transaction_date', $thisMonth->month)
            ->whereHas('category', fn($q) => $q->where('type', 'expense'))
            ->get();

        if ($transactions->isEmpty()) return "Belum ada data transaksi bulan ini untuk dianalisis.";

        $breakdown = $transactions
            ->groupBy(fn($t) => $t->category?->name ?? 'Lainnya')
            ->map(fn($g) => $g->sum('amount'))
            ->sortDesc()
            ->map(fn($t, $cat) => "- {$cat}: Rp " . number_format($t, 0, ',', '.'))
            ->values()->join("\n");

        $prompt = <<<PROMPT
Berdasarkan pengeluaran bulan ini:
{$breakdown}

Berikan 3 saran hemat yang spesifik, realistis, dan langsung bisa diterapkan oleh orang Indonesia.
Setiap saran harus menyebutkan estimasi penghematan dalam Rupiah per bulan.
Gunakan bahasa Indonesia yang ramah dan emoji yang relevan. Format sebagai bullet point.
PROMPT;

        try {
            $response = Http::withoutVerifying()->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
                [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.5],
                ]
            );

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }
        } catch (\Exception $e) {
            Log::error('Gemini Saving Tips Error: ' . $e->getMessage());
        }

        return null;
    }
}
