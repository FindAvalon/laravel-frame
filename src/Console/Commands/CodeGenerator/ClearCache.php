<?php

namespace Longway\Frame\Console\Commands\CodeGenerator;


use Illuminate\Console\Command;

class ClearCache extends Command
{
    protected $signature = 'code:cache-clear {name}';

    protected $description = '清除缓存';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $source = new Source();

        foreach ( $source as $item ) {
            $this->info($item);
        }
    }
}