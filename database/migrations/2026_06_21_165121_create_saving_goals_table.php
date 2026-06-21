<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('saving_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');                          // "Beli Laptop", "Liburan Bali"
            $table->string('icon', 10)->default('🎯');      // emoji icon
            $table->decimal('target_amount', 15, 2);        // target total
            $table->decimal('current_amount', 15, 2)->default(0); // already saved
            $table->date('deadline')->nullable();            // target date
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_goals');
    }
};
