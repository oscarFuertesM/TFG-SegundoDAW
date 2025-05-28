<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

   
    protected $table = 'usuarios'; 
    protected $primaryKey = 'idUsuario';
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombreUsuario', 
        'mailUsuario', 
        'passUsuario', 
        'permisos',
    ];

    /**
     * Los atributos que deberían ser ocultados para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passUsuario', 
        'remember_token', 
    ];
    

    /**
     * Desactivar los timestamps si no los usas.
     *
     * @var bool
     */
    public $timestamps = false; 

    /**
     * Definir cómo debe formatearse la contraseña cuando se asigna al modelo.
     *
     * @param string $value
     */
    public function setPassUsuarioAttribute($value)
    {
        
        $this->attributes['passUsuario'] = bcrypt($value);
    }

    /**
     * Para autenticar usando el correo electrónico (si lo necesitas).
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'mailUsuario'; // Usar 'mailUsuario' para la autenticación
    }

    /**
     * Obtener la contraseña para la autenticación.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->passUsuario; 
    }

    public function isAdmin(): bool
{
    return $this->permisos === 7;
}


public function equiposRelacion()
{
    return $this->belongsToMany(Equipo::class, 'usuarioequipos', 'idUsuario', 'idEquipo');
}



}

