<?php

namespace App\Exports;

use App\Models\Emprendimiento;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmprendimientoExport implements FromCollection, WithHeadings
{
    protected $estado;
    protected $favoritos;

    // Constructor para recibir el estado o condición de favoritos
    public function __construct($filtro = null)
    {
        $this->favoritos = $filtro === 'favoritos';
        $this->estado = $filtro === 'VERIFICADO' ? $filtro : null;
    }

    /**
     * Selecciona los datos específicos a exportar.
     */
    public function collection()
    {
        $query = Emprendimiento::with(['emprendedor.carrera', 'categoria', 'estado_emp']);

        // Filtrar por favoritos si es necesario
        if ($this->favoritos) {
            $query->whereHas('preferencias', function ($q) {
                $q->where('estudiante_id', Auth::id())->where('favorito', true);
            });
        }

        // Filtrar por estado si se proporciona
        if ($this->estado) {
            $query->whereHas('estado_emp', function ($q) {
                $q->where('nombre', $this->estado);
            });
        }

        return $query->get()->map(function ($emprendimiento) {
            return [
                'nombre' => $emprendimiento->nombre,
                'descripcion' => $emprendimiento->descripcion,
                'estado' => $emprendimiento->estado_emp->nombre,
                'emprendedor' => $emprendimiento->emprendedor->nombre . ' ' . $emprendimiento->emprendedor->apellido,
                'carrera' => $emprendimiento->emprendedor->carrera->nombre,
                'telefono' => $emprendimiento->emprendedor->celular,
                'categoria' => $emprendimiento->categoria->nombre,
            ];
        });
    }

    /**
     * Define los encabezados para las columnas exportadas.
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Descripción',
            'Estado',
            'Emprendedor',
            'Carrera',
            'Teléfono',
            'Categoría',
        ];
    }
}
