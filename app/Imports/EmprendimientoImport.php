<?php

namespace App\Imports;

use App\Models\Emprendimiento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmprendimientoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Emprendimiento([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'categoria' => $row['categoria'],
            'estado' => $row['estado'],
            // 
        ]);
    }
}
