<?php

namespace App\Exports;

use App\Models\Carrera;
use Maatwebsite\Excel\Concerns\FromCollection;

class CarreraExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Carrera::all();
    }
}
