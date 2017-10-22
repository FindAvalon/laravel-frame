<?php

namespace Longway\Frame\Providers;

use Illuminate\Support\ServiceProvider;
use Longway\Frame\Console\Commands\CodeGenerator\Build;
use Longway\Frame\Console\Commands\CodeGenerator\Check;
use Longway\Frame\Console\Commands\CodeGenerator\ClearCache;
use Longway\Frame\Console\Commands\CodeGenerator\SourceList;

class FrameProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(SourceList::class);
        $this->commands(Build::class);
        $this->commands(ClearCache::class);
        $this->commands(Check::class);
    }
}