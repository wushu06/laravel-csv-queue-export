<?php

namespace Nour\Export;

use Illuminate\Routing\Pipeline;

/**
 * Class Export
 */
class Export
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var
     */
    public $object;

    /**
     * @var array
     */
    public $claimJobs = [];

    /**
     * @param object $object
     * @param string|null $email
     *
     * @return void
     */
    public function export(object $object, string $email = null): void
    {
        $this->email = $email ?? config('export.email');
        $this->object = $object;

        app(Pipeline::class)
            ->send($this)
            ->through(
                [
                    ModelExport::class,
                    ModelQueue::class,
                ]
            )
            ->then(
                function ($content) {
                    logger('Total queue dispatched: '.count($content));
                }
            );
    }
}
