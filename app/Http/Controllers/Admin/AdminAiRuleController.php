<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiRule;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminAiRuleController extends Controller
{
    public function index()
    {
        $rules = AiRule::with('category')->whereNull('user_id')->latest()->paginate(20);
        $categories = Category::whereNull('user_id')->get();
        return view('admin.ai-rules.index', compact('rules', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'keyword' => 'required|string|max:100',
        ]);

        AiRule::create([
            'category_id' => $request->category_id,
            'keyword' => strtolower(trim($request->keyword)),
            'user_id' => null,
        ]);

        return back()->with('success', "Rule kata kunci \"{$request->keyword}\" berhasil ditambahkan!");
    }

    public function destroy(AiRule $aiRule)
    {
        $aiRule->delete();
        return back()->with('success', 'Rule berhasil dihapus.');
    }
}
