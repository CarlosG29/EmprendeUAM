<?php

namespace App\Exports;

use App\Models\Estudiante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstudianteExport implements FromCollection, WithHeadings
{
    /**
     * Selecciona los datos específicos a exportar.
     */
    public function collection()
    {
        return Estudiante::with('carrera')->get()->map(function ($estudiante) {
            return [
                'cif' => $estudiante->cif,
                'nombre' => $estudiante->nombre,
                'apellido' => $estudiante->apellido,
                'carrera' => $estudiante->carrera->nombre,
                'email' => $estudiante->email,
            ];
        });
    }

    /**
     * Define los encabezados para las columnas exportadas.
     */
    public function headings(): array
    {
        return [
            'CIF',
            'Nombre',
            'Apellido',
            'Carrera',
            'email',
        ];
    }
}
