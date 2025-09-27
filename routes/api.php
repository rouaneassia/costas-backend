<?php

use App\Http\Controllers\EquipeController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\RendezVousController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});


Route::resource('publications',PublicationController::class);
Route::resource('equipes',EquipeController::class);
Route::resource('rendezvous', RendezVousController::class);

// Route::resource('rendezvous',RendezVousController::class)->middleware('auth:sanctum');
// Route::post('/rendezvous', [RendezVousController::class, 'store']);

