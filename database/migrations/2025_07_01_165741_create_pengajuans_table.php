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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('amount');
            $table->foreignId('user_id')
                ->constrained('users');
            $table->foreignId('category_id')
                ->constrained('categories');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('file')->nullable();
            $table->timestamp('submited_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};