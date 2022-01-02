<?php

namespace Nour\Export\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ExportQueue
{
    public function model(): Builder;

    public function rowMapping($row): array;
}
