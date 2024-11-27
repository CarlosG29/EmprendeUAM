<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CarreraImport;
use App\Exports\CarreraExport;
use Maatwebsite\Excel\Facades\Excel;

class CarreraController extends Controller
{
    /**
     * Importar datos desde un archivo XLS
     */
    public function importCarrera(Request $request)
    {
        // Validación del archivo
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        // Importación de datos
        Excel::import(new CarreraImport, $request->file('file'));

        return redirect()->back()->with('success', 'Datos importados correctamente');
    }

    /**
     * Exportar datos a un archivo XLS
     */
    public function exportCarrera()
    {
        return Excel::download(new CarreraExport, 'carreras.xlsx');
    }
}
