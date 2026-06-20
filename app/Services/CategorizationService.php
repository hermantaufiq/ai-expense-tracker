<?php

namespace App\Services;

use App\Models\AiRule;
use App\Models\Category;

class CategorizationService
{
    /**
     * Detect category based on description using rule-based keyword matching.
     *
     * @param string $description
     * @return int|null Category ID
     */
    public function detectCategory(string $description): ?int
    {
        $description = strtolower($description);
        
        $personalRules = AiRule::where('user_id', auth()->id())->get();
        $globalRules = AiRule::whereNull('user_id')->get();
        
        $rules = $personalRules->concat($globalRules);

        foreach ($rules as $rule) {
            $keyword = strtolower($rule->keyword);
            
            // Simple keyword matching
            if (strpos($description, $keyword) !== false) {
                return $rule->category_id;
            }
        }

        // If no match found, find or create 'Others' category
        $defaultCategory = Category::firstOrCreate(
            ['name' => 'Others'],
            ['type' => 'expense', 'color_code' => '#9ca3af'] // default tailwind gray-400
        );

        return $defaultCategory->id;
    }
}
