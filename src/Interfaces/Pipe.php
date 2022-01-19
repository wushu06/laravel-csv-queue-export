<?php

namespace Nour\Export\Interfaces;

use Closure;

interface Pipe
{
    public function handle($export, Closure $next);
}
