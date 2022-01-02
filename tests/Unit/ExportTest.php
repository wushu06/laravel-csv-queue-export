<?php

namespace Nour\Export\Tests\Unit;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Nour\Export\Jobs\ExportJob;
use Nour\Export\Tests\TestCase;

class ExportTest extends TestCase
{

    /** @test */
    function it_dispatches_the_export_job()
    {
        Bus::fake();
        $job = (new ExportJob(new ExampleExport()))
            ->setIterator(10);
        $job::dispatch(new ExampleExport());
        Bus::assertDispatched(ExportJob::class);

    }

    /** @test */
    function it_creates_a_csv_file()
    {
        $job = (new ExportJobFake(new ExampleExport()))
            ->setIterator(10);
        $job::dispatch(new ExampleExport());
        $job->handle();
        $this->assertFileExists('./export.csv');
        File::delete('./export.csv');
    }
    /** @test */
    function it_convert_class_to_snake_name()
    {
        $job = $this->getMockBuilder(ExportJob::class)
            ->disableOriginalConstructor()
            ->getMock();

        $job->expects(self::once())
            ->method('toSnakeCase')
            ->with(ExportJob::class)
            ->willReturn('export_job');

        $job->toSnakeCase(ExportJob::class);

    }
}

/*
 * we pass a collection instead of query builder because we dont have a migration
 */
class ExportJobFake extends ExportJob
{
    protected function getData()
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
        return ExportModel::hydrate($collection);
    }
}
