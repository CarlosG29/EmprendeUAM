<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;


class Estudiante extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'cif',
        'nombre',
        'apellido',
        'email',
        'password',
        'celular',
        'carrera_id',
        'foto_perfil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guarded = ['admin'];

    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class, 'emprendedor_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
    public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}
}
