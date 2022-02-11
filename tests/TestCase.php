<?php

namespace Nour\Export\Tests;

use Nour\Export\ExportServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ExportServiceProvider::class,
        ];
    }
}
