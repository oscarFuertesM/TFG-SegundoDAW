<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioEquipo extends Model
{
    protected $table = 'usuarioequipos';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'idUsuario',
        'idEquipo',
    ];
}
