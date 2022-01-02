<?php

namespace Nour\Export\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Nour\Export\Interfaces\ExportQueue;

class ExampleExport implements ExportQueue
{

    public function model(): Builder
    {
        $collection = [
            [
                'id' => 1,
                'name' => 'nour'
            ],
            [
                'id' => 2,
                'name' => 'adam'
            ],
            [
                'id' => 3,
                'name' => 'hajar'
            ]
        ];
        return ExportModel::hydrate($collection)->toQuery();
    }

    public function rowMapping($row): array
    {
        return [
            'ID' => $row->id,
            'Name' => $row->name,
        ];
    }

    public function file()
    {
        return './export.csv';
    }

}
