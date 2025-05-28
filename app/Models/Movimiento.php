<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'id';

    public $incrementing = true; 
    public $timestamps = false; 

    protected $fillable = [
        'id',
        'nombre',
        'tipo',
    ];
}
