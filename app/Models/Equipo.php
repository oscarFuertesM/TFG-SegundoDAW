<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $primaryKey = 'idEquipo'; 

    public $incrementing = true; 
    public $timestamps = false; 

    protected $fillable = [
        'idEquipo',
        'datosEquipo',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuarioequipos', 'idEquipo', 'idUsuario');
    }

}
