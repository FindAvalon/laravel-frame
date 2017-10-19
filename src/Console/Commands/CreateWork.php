<?php

namespace Longway\Frame\Console\Commands;

use Illuminate\Console\Command;
use Longway\Laravel\Frame\Services\FrameException;
use Longway\Laravel\Frame\Services\FrameService;
use Storage;

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
//        Storage::disk('local')->put('frame/file.txt', 'Contents');

        $service = new FrameService();

        try {
            $service->create('apps.vote');
            $this->info('创建成功');
        } catch ( FrameException $e ) {
            $this->error($e->getMessage());
        }
    }
}