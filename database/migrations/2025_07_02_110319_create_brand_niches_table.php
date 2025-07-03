<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_niches', function (Blueprint $table) {
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('niche_id')->constrained()->onDelete('cascade');
            $table->primary(['brand_id', 'niche_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_niches');
    }
};