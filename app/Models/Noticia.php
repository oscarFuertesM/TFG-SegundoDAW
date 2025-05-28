<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias'; 
    protected $primaryKey = 'idnoticias'; 

    public $incrementing = true; 
    public $timestamps = false; 

    protected $fillable = [
        'idnoticias',
        'titulo',
        'texto',
    ];



}
