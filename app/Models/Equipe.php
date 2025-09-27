<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    protected $fillable = [
        'name_ar',
        'name_fr',
        'date',
        'email',
        'image',
        'bio_ar',
        'bio_fr',
        'bio_en',
        'linkedin',
        'facebook',
        'instagram',
    ];
    public function rendezvous (){
        return $this->hasMany(RendezVous::class);
    }
}
