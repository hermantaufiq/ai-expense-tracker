<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AiRule;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin Expense Tracker',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_premium' => true,
        ]);

        // Create Regular Free User
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_premium' => false,
        ]);

        // Global Income Categories (user_id = null)
        $gaji = Category::create(['name' => 'Gaji', 'type' => 'income', 'color_code' => '#10b981']);
        $bonus = Category::create(['name' => 'Bonus', 'type' => 'income', 'color_code' => '#34d399']);

        // Global Expense Categories
        $makanan = Category::create(['name' => 'Makanan & Minuman', 'type' => 'expense', 'color_code' => '#f43f5e']);
        $transport = Category::create(['name' => 'Transportasi', 'type' => 'expense', 'color_code' => '#3b82f6']);
        $belanja = Category::create(['name' => 'Belanja', 'type' => 'expense', 'color_code' => '#8b5cf6']);
        $tagihan = Category::create(['name' => 'Tagihan', 'type' => 'expense', 'color_code' => '#f59e0b']);
        Category::create(['name' => 'Lainnya', 'type' => 'expense', 'color_code' => '#6b7280']);

        // Global AI Rules for Income
        AiRule::create(['category_id' => $gaji->id, 'keyword' => 'gaji']);
        AiRule::create(['category_id' => $gaji->id, 'keyword' => 'salary']);
        AiRule::create(['category_id' => $bonus->id, 'keyword' => 'bonus']);
        AiRule::create(['category_id' => $bonus->id, 'keyword' => 'thr']);

        // Global AI Rules for Expenses
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'makan']);
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'minum']);
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'kopi']);
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'starbucks']);
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'gofood']);
        AiRule::create(['category_id' => $makanan->id, 'keyword' => 'grabfood']);
        
        AiRule::create(['category_id' => $transport->id, 'keyword' => 'bensin']);
        AiRule::create(['category_id' => $transport->id, 'keyword' => 'gojek']);
        AiRule::create(['category_id' => $transport->id, 'keyword' => 'grab']);
        AiRule::create(['category_id' => $transport->id, 'keyword' => 'parkir']);
        AiRule::create(['category_id' => $transport->id, 'keyword' => 'tol']);

        AiRule::create(['category_id' => $belanja->id, 'keyword' => 'shopee']);
        AiRule::create(['category_id' => $belanja->id, 'keyword' => 'tokopedia']);
        AiRule::create(['category_id' => $belanja->id, 'keyword' => 'baju']);
        AiRule::create(['category_id' => $belanja->id, 'keyword' => 'sepatu']);

        AiRule::create(['category_id' => $tagihan->id, 'keyword' => 'listrik']);
        AiRule::create(['category_id' => $tagihan->id, 'keyword' => 'air']);
        AiRule::create(['category_id' => $tagihan->id, 'keyword' => 'internet']);
        AiRule::create(['category_id' => $tagihan->id, 'keyword' => 'wifi']);
        AiRule::create(['category_id' => $tagihan->id, 'keyword' => 'pln']);
    }
}
