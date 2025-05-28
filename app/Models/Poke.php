<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poke extends Model
{
    protected $table = 'pokes';       
    protected $primaryKey = 'pokedex';  

    public $incrementing = false;       
    public $timestamps = false;        

    protected $fillable = [
        'pokedex',
        'nombre',
        'tipo1',
        'tipo2',
    ];
}

