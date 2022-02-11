<?php

namespace Nour\Export\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool export(object $export, string $email)
 */
class Export extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'export';
    }
}
