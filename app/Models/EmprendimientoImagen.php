<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmprendimientoImagen extends Model
{
    use HasFactory;

    protected $table = 'emprendimiento_imagenes'; // Nombre exacto de la tabla en la base de datos

    protected $fillable = ['emprendimiento_id', 'path'];

    /**
     * Relación con el modelo Emprendimiento.
     * Una imagen pertenece a un único emprendimiento.
     */
    public function emprendimiento()
    {
        return $this->belongsTo(Emprendimiento::class, 'emprendimiento_id');
    }
}


    




