<?php

namespace Longway\Frame\Console\Commands\CodeGenerator;


use Illuminate\Console\Command;
use Longway\Frame\Develop\CodeGenerator\Cache\Cache;
use Longway\Frame\Develop\CodeGenerator\Source\Source;

class Check extends Command
{
    protected $signature = 'code:check {name}';

    protected $description = '查看';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');

        $cache = new Cache();
        $source = new Source();

        $filename = $source->getFilename($name);

        foreach ( $cache->get($filename) as $item ) {
            $this->info($item['date']."\t".$item['filename']);
        }
    }
}