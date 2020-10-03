<?php

namespace Modules\WareHouse\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class StockImport implements ToCollection
{

    /**
     * @inheritDoc
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
