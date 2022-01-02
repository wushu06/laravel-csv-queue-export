<?php

namespace Nour\Export;

use Illuminate\Support\Facades\Bus;
use Nour\Export\Jobs\ExportJob;
use Nour\Export\Jobs\NotifyUser;
use Prophecy\Exception\Doubler\ClassNotFoundException;

class Export
{
        public function export($object, $email = null): void
    {
        $email = $email ?? config('export.email');
        $model = $object->model();
//        if (!class_exists($model)) {
//            throw new ClassNotFoundException('Class not found!', $model);
//        }

        $modelCount = $model->count();

        $claimsJobs = [];
        for ($i = 0; $i <= $modelCount; $i += config('export.limit')) {
            $claimsJobs[] = (new ExportJob($object))
                ->setIterator($i);
        }


        try {
            Bus::batch($claimsJobs)->then(function () use ($email) {
                if ($email) {
                    dispatch(new NotifyUser('Claim export has finished!', $email));
                }
            })->catch(function () use ($email) {
                if ($email) {
                    dispatch(new NotifyUser('Claim export has failed!', $email));
                }
            })->dispatch();
        } catch (\Throwable $e) {
            logger($e->getMessage());
        }
    }

}
