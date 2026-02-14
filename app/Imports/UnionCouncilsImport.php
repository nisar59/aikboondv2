<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class UnionCouncilsImport implements ToArray,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
     
   public function array(array $array)
    {
        return $array;
    }
}
