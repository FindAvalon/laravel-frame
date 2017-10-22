<?php

namespace Longway\Frame\Develop\CodeGenerator;

use Longway\Frame\Develop\CodeGenerator\Cache\Cache;
use Longway\Frame\Develop\CodeGenerator\Parser\Parser;
use Longway\Frame\Develop\CodeGenerator\Source\Source;
use Longway\Frame\Develop\CodeGenerator\Compiler\Compiler;
use Longway\Frame\Develop\DevelopException;

class Service
{

    protected $parser;
    protected $source;
    protected $compiler;
    protected $cache;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->source = new Source();
        $this->compiler = new Compiler();
        $this->cache = new Cache();
    }

    public function build($name)
    {
        if ( !$path = $this->source->getFilename($name) ) {
            throw new DevelopException('未找到指定源文件');
        }
        $data =  $this->parser->load($path);
        $this->compiler->exec($data);
        $this->cache->set($path);
    }

    /**
     * 解析名称
     * @param $name 名称（group.user）
     * @return array
     */
    protected function analysisName($name)
    {
        $result = explode('.', $name);
        $result = array_map(function ($item) {
            return ucfirst(strtolower($item));
        }, $result);
        return join('/', $result);
    }
}