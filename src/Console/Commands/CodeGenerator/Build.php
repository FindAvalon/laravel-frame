<?php

namespace Longway\Frame\Console\Commands\CodeGenerator;


use Illuminate\Console\Command;
use Longway\Frame\Develop\DevelopException;
use Longway\Frame\Develop\CodeGenerator\Service;

class Build extends Command
{
    protected $signature = 'code:build {name}';

    protected $description = '生成代码';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $service = new Service();
        try {
            $data = $service->build($name);
        } catch ( DevelopException $e ) {
            $this->error($e->getMessage());
        }

    }
}