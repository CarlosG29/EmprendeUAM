<?php

namespace App\Imports;

use App\Models\Carrera;
use Maatwebsite\Excel\Concerns\ToModel;

class CarreraImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Carrera([
            //
        ]);
    }
}
