<?php

namespace Nour\Export;

use Closure;
use Nour\Export\Interfaces\Pipe;
use Nour\Export\Jobs\ExportJob;

class ModelExport implements Pipe
{
    /**
     * @param $export
     * @param Closure $next
     * @return mixed
     */
    public function handle($export, Closure $next)
    {
        $model = $export->object->model();
        $modelCount = $model->count();

        for ($i = 0; $i <= $modelCount; $i += config('export.limit') ?? 500) {
            $export->claimsJobs[] = (new ExportJob($export->object))
                ->setIterator($i);
        }

        return $next($export);
    }
}
