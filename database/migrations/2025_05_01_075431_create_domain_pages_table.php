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
        Schema::create('domain_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('domains_id')->constrained('domains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_pages');
    }
};
