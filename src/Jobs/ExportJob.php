<?php

namespace Nour\Export\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Nour\Export\Interfaces\ExportQueue;

class ExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected int $limit;

    /**
     * @var int
     */
    protected int $iterator = 0;

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var ExportQueue
     */
    protected ExportQueue $object;

    private $model;

    /**
     * ExportJob constructor.
     * @param ExportQueue $object
     */
    public function __construct(ExportQueue $object)
    {
        $this->object = $object;
        $this->fileName = method_exists($object, 'file') ? $object->file() : $this->getFile();
        $this->limit = config('export.limit');
    }

    /**
     * @param $iterator
     * @return $this
     */
    public function setIterator($iterator): ExportJob
    {
        $this->iterator = $iterator;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit): ExportJob
    {
        $this->limit = $limit;
        return $this;
    }

    public function toSnakeCase(string $camelCase): string
    {
        return  strtolower(
            preg_replace(
                ['/([A-Z]+)/', '/_([A-Z]+)([A-Z][a-z])/'],
                ['_$1', '_$1_$2'],
                lcfirst($camelCase)
            )
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }
        $this->writeToCsvFile();
    }

    protected function getData()
    {
        $data = $this->object->model()
            ->skip($this->iterator)
            ->limit($this->limit);

        return $data->get();
    }

    protected function writeToCsvFile(): void
    {
        $data = $this->getData();

        if (count($data) > 0) {
            if (!File::exists($this->fileName)) {
                $fp = fopen($this->fileName, 'wb');
            } else {
                $fp = fopen($this->fileName, 'ab');
            }
            $i = 0;
            foreach ($data as $row) {
                $fields = $this->object->rowMapping($row);
                if ($i === 0 &&  $this->iterator === 0) {
                    fputcsv($fp, array_keys($fields));
                }
                fputcsv($fp, array_values($fields));
                $i++;
            }
            fclose($fp);
        }
    }

    private function getFile(): string
    {
        return storage_path('app/' . $this->toSnakeCase(class_basename($this->object)) . '_queue_export.csv');
    }
}
