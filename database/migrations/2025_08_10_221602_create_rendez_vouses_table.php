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
        Schema::create('rendez_vouses', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone');
            $table->date('date');
            $table->time('heure');
            $table->text('sujet');
            $table->string('email');
            $table->unsignedBigInteger('equipe_id');
            $table->foreign("equipe_id")->references('id')->on("equipes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vouses');
    }
};
