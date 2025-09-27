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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['image', 'video', 'document']);
            $table->string('date');
            $table->string('title_ar');
            $table->string('title_fr');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->string('file_path')->nullable(); 
            $table->string('video_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
