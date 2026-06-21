<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Socialite Routes
Route::get('/auth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])->name('social.callback');

// ─── Admin Auth (tidak perlu login dulu) ───────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/transactions/ai', [TransactionController::class, 'storeAi'])->name('transactions.storeAi');
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports', [\App\Http\Controllers\FullReportController::class, 'index'])->name('reports.index');
    Route::resource('budgets', \App\Http\Controllers\BudgetController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/analysis', [\App\Http\Controllers\AnalysisController::class, 'index'])->name('analysis.index');
    Route::post('/analysis/roast', [\App\Http\Controllers\AnalysisController::class, 'roast'])->name('analysis.roast');
    Route::post('/analysis/tips', [\App\Http\Controllers\AnalysisController::class, 'tips'])->name('analysis.tips');
    Route::resource('saving-goals', \App\Http\Controllers\SavingGoalController::class)->only(['index', 'store', 'destroy']);
    Route::post('/saving-goals/{savingGoal}/add-funds', [\App\Http\Controllers\SavingGoalController::class, 'addFunds'])->name('saving-goals.add-funds');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-ban', [App\Http\Controllers\Admin\AdminUserController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::post('/users/{user}/toggle-premium', [App\Http\Controllers\Admin\AdminUserController::class, 'togglePremium'])->name('users.toggle-premium');
    Route::get('/ai-rules', [App\Http\Controllers\Admin\AdminAiRuleController::class, 'index'])->name('ai-rules.index');
    Route::post('/ai-rules', [App\Http\Controllers\Admin\AdminAiRuleController::class, 'store'])->name('ai-rules.store');
    Route::delete('/ai-rules/{aiRule}', [App\Http\Controllers\Admin\AdminAiRuleController::class, 'destroy'])->name('ai-rules.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
