<?php

namespace Longway\Frame\Console\Commands\CodeGenerator;

use Illuminate\Console\Command;
use Longway\Frame\Develop\CodeGenerator\Source\Source;

class SourceList extends Command
{
    protected $signature = 'code';

    protected $description = '查看列表';

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