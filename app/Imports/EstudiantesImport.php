<?php

namespace App\Imports;

use App\Models\Estudiante;
use App\Models\Carrera;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EstudiantesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info("Procesando fila: " . json_encode($row));

        // Validación de carrera vacía
        if (empty($row['carrera'])) {
            Log::warning("Carrera vacía en CIF: {$row['cif']} - Saltando fila.");
            return null;
        }

        // Verificar si el estudiante ya existe
        $existingEstudiante = Estudiante::where('cif', $row['cif'])->orWhere('email', $row['email'])->first();
        if ($existingEstudiante) {
            Log::info("Estudiante ya existente: " . $row['cif'] . " - Saltando fila.");
            return null;
        }

        // Buscar o crear la carrera
        $carrera = Carrera::firstOrCreate(['nombre' => $row['carrera']]);

        // Crear el estudiante con una contraseña predeterminada y otros valores
        return new Estudiante([
            'cif'        => $row['cif'],
            'nombre'     => $row['nombre'],
            'apellido'   => $row['apellido'],
            'carrera_id' => $carrera->id,
            'email'      => $row['email'],
            'status'     => true,
            'celular'    => $row['celular'] ?? '', // Valor por defecto o vacío para celular
            'password'   => Hash::make('12345678'), // Contraseña predeterminada encriptada
        ]);
    }
}
