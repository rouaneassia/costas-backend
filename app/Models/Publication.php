<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'type',
        'title_ar', 'title_fr', 'title_en',
        'description_ar', 'description_fr', 'description_en',
        'file_path', 'video_url','date'
    ];
}
