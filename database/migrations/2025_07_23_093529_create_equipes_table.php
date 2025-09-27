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
        Schema::create('equipes', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_fr');
            $table->date('date')->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('image')->nullable();
            $table->text('bio_ar')->nullable();
            $table->text('bio_fr')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipes');
    }
};
