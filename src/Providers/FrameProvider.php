<?php

namespace Longway\Frame\Providers;

use Illuminate\Support\ServiceProvider;
use Longway\Laravel\Frame\Console\Commands\CreateWork;

class FrameProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(CreateWork::class);
    }
}