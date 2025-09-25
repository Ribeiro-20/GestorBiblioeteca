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
        Schema::create('renovacoes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('emprestimo_id')->constrained('emprestimos')->onDelete('cascade');
    $table->date('data_renovacao');
    $table->date('nova_data_limite');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renovacoes');
    }
};
