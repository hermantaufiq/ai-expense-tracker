<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiTransactionService
{
    /**
     * Parses a natural language input into a structured transaction array.
     *
     * @param string $text The natural language input (e.g., "Makan siang 50rb kemarin")
     * @return array|null Returns array containing amount, description, transaction_date, category_id/new_category_name, type.
     */
    public function parseTransaction(string $text): ?array
    {
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            Log::error('Gemini API key is not configured.');
            return null;
        }

        // Ambil daftar kategori yang ada milik user dan yang global
        $categories = Category::whereNull('user_id')->orWhere('user_id', auth()->id())->get(['id', 'name', 'type']);
        $categoriesList = $categories->map(function ($cat) {
            return "ID: {$cat->id}, Name: {$cat->name}, Type: {$cat->type}";
        })->implode("\n");

        $today = now()->format('Y-m-d');

        $prompt = <<<PROMPT
You are a financial assistant expert in parsing natural language into transaction data.
Parse the following text from an Indonesian user into a structured JSON format.
Today's date is: {$today}. Calculate any relative dates (like 'kemarin', 'hari ini', 'minggu lalu') based on this date.

Here is the list of available categories:
{$categoriesList}

The output MUST be a valid JSON object without any markdown wrapping or code blocks. The JSON must have these exact keys:
- amount: (float) the transaction amount extracted from the text (e.g. 50rb = 50000)
- description: (string) a concise description of the transaction
- transaction_date: (string) the date in 'YYYY-MM-DD' format
- type: (string) either 'expense' or 'income'
- category_id: (integer or null) the ID of the best matching category from the list above. If no suitable category exists, set it to null.
- new_category_name: (string or null) if category_id is null, suggest a short appropriate name for the new category (e.g., "Makanan", "Transportasi"). If category_id is provided, set this to null.

Text to parse: "{$text}"
PROMPT;

        try {
            $response = Http::withoutVerifying()->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $jsonString = $responseData['candidates'][0]['content']['parts'][0]['text'];
                    $parsed = json_decode($jsonString, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $parsed;
                    } else {
                        Log::error('Gemini returned invalid JSON: ' . $jsonString);
                    }
                }
            } else {
                Log::error('Gemini API Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Gemini Transaction Parsing Exception: ' . $e->getMessage());
        }

        return null;
    }
}
