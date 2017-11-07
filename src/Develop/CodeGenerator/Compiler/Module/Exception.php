<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;


use Longway\Frame\Develop\CodeGenerator\Compiler\Compiler;

class Exception implements ModuleInterface
{
    use ModuleTrait;

    const NAMESPACE_PREFIX = 'App\\Exceptions';

    protected $name = 'exception';

    public function parse(Compiler $compiler, $data)
    {
        $this->compiler = $compiler;
        $this->data = $data;

        $result = [
        ];
        $result = array_merge($result, $compiler->info);
        $globalSign = $result['global'] ?? false;
        unset($result['global']);

        if ( !$globalSign ) {
            $result['namespace'] = empty($result['namespace']) ? self::NAMESPACE_PREFIX : self::NAMESPACE_PREFIX.$result['namespace'];
        }
        $result['name'] .= 'Exception';
        $content = $compiler->template($this->name, 'index', $result);
        if ( $dir = $this->compiler->getDir($result['namespace']) ) {
            if ( !file_exists($dir) ) mkdir($dir);
            $filename = $result['name'].'.php';
            file_put_contents($dir."/" .$filename, $content);
            $this->compiler->moduleInfo['exception'] = [
                'namespace' => $result['namespace'],
                'name'      => $result['name']
            ];
        }
    }
}