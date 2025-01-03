<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'comentario',
        'estudiante_id',
        'emprendimiento_id'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function emprendimiento()
    {
        return $this->belongsTo(Emprendimiento::class, 'emprendimiento_id');
    }

    // Accesor para verificar si el comentario fue editado
    public function getEditadoAttribute()
    {
        return $this->updated_at->gt($this->created_at);
    }
}
    