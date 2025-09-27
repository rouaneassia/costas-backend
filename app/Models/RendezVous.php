<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model

{
    protected $fillable = ['nom', 'telephone', 'date', 'heure', 'sujet','email','equipe_id'];
    public function equipe(){
        return $this->belongsTo(Equipe::class);
    }
}
