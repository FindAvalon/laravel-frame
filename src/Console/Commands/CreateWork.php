<?php

namespace Longway\Laravel\Frame\Console\Commands;

use Illuminate\Console\Command;

class CreateWork extends Command
{
    protected $signature = 'make:work';

    protected $description = 'make work';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('ok');
    }
}