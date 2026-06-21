<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'statement_file' => 'required|file|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        $file = $request->file('statement_file');
        $content = file_get_contents($file->getRealPath());

        // We will extract text from CSV/TXT. 
        // For simple text extraction, we just pass the raw content to Gemini if it's not too large.
        $textLimit = 15000; // Limit characters to avoid token limits
        if (strlen($content) > $textLimit) {
            $content = substr($content, 0, $textLimit);
        }

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return back()->with('error', 'API Key Gemini belum disetting.');
        }

        $prompt = "Berikut adalah raw data mutasi bank (CSV/Text):\n\n" . $content . "\n\n"
            . "Tugasmu: Ekstrak semua transaksi keuangan dari teks tersebut. Abai kan saldo awal/akhir, hanya ambil mutasinya.\n"
            . "Format output HARUS array JSON valid (tanpa markdown), contoh:\n"
            . "[\n"
            . "  {\"date\": \"YYYY-MM-DD\", \"description\": \"Transfer ke Budi\", \"amount\": 50000, \"type\": \"expense\"},\n"
            . "  {\"date\": \"YYYY-MM-DD\", \"description\": \"Gaji Bulanan\", \"amount\": 5000000, \"type\": \"income\"}\n"
            . "]\n"
            . "Jika nominal negatif atau berupa pengeluaran, type='expense' dan amount positif. Jika pemasukan, type='income'. Pastikan format tanggal YYYY-MM-DD.";

        try {
            $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.1, // Low temperature for factual extraction
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                return back()->with('error', 'Gagal memproses data dengan AI.');
            }

            $resultText = $response->json('candidates.0.content.parts.0.text');
            
            // Clean up markdown block if exists
            $resultText = str_replace(['```json', '```'], '', $resultText);
            $transactionsData = json_decode(trim($resultText), true);

            if (!$transactionsData || !is_array($transactionsData)) {
                return back()->with('error', 'Gagal memparsing respons dari AI. Pastikan format file terbaca.');
            }

            $importedCount = 0;
            $defaultExpenseCat = Category::where('type', 'expense')->first();
            $defaultIncomeCat = Category::where('type', 'income')->first();

            foreach ($transactionsData as $t) {
                if (!isset($t['amount']) || !isset($t['description']) || !isset($t['date'])) continue;
                
                $type = $t['type'] ?? 'expense';
                $amount = abs((float) $t['amount']);
                if ($amount == 0) continue;

                // Simple date parsing
                try {
                    $date = Carbon::parse($t['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $date = now()->format('Y-m-d');
                }

                $catId = $type === 'expense' 
                    ? ($defaultExpenseCat->id ?? null) 
                    : ($defaultIncomeCat->id ?? null);

                // Check for duplicates (same date, amount, description snippet)
                $exists = Transaction::where('user_id', auth()->id())
                    ->where('transaction_date', $date)
                    ->where('amount', $amount)
                    ->where('description', 'like', substr($t['description'], 0, 15) . '%')
                    ->exists();

                if (!$exists) {
                    Transaction::create([
                        'user_id' => auth()->id(),
                        'category_id' => $catId,
                        'amount' => $amount,
                        'description' => '[Import] ' . $t['description'],
                        'transaction_date' => $date,
                    ]);
                    $importedCount++;
                }
            }

            return redirect()->route('transactions.index')
                ->with('success', "Berhasil mengimpor $importedCount transaksi baru dari mutasi bank via AI!");

        } catch (\Exception $e) {
            Log::error('Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses file.');
        }
    }
}
