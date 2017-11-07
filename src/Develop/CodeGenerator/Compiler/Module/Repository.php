<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;

use Longway\Frame\Develop\CodeGenerator\Compiler\Compiler;

class Repository implements ModuleInterface
{
    use ModuleTrait;

    const NAMESPACE_PREFIX = 'App\\Repositories';

    protected $name = 'repository';

    public function parse(Compiler $compiler, $data)
    {
        $this->compiler = $compiler;
        $this->data = $data;

        $result = [
            'searchable'        => $this->searchable()
        ];
        $result = array_merge($result, $compiler->info);
        $globalSign = $result['global'] ?? false;
        unset($result['global']);

        if ( !$globalSign ) {
            $result['namespace'] = empty($result['namespace']) ? self::NAMESPACE_PREFIX : self::NAMESPACE_PREFIX.$result['namespace'];
        }
        $result['name'] .= 'Repository';
        $result['modelNamespace'] = $this->compiler->moduleInfo['model']['namespace'] ?? '';
        $result['modelName'] = $this->compiler->moduleInfo['model']['name'] ?? '';
        $content = $compiler->template($this->name, 'index', $result);
        if ( $dir = $this->compiler->getDir($result['namespace']) ) {
            if ( !file_exists($dir) ) mkdir($dir);
            $filename = $result['name'].'.php';
            file_put_contents($dir."/" .$filename, $content);
            $this->compiler->moduleInfo['repository'] = [
                'namespace' => $result['namespace'],
                'name'      => $result['name']
            ];
        }
    }

    public function searchable()
    {
        if ( $data = $this->compiler->getParam($this->data, 'searchable') ) {
            $arr = [];
            foreach ( $data as $key => $value ) {
                if ( is_string($value) || is_numeric($value) ) {
                    $arr[] = "'{$key}' => '{$value}'";
                } elseif ( is_array($value) ) {
                    $value = join("','", $value);
                    $valueStr = "['{$value}']";
                    $arr[] = "'{$key}' => {$valueStr}";
                }
            }
            $content = join(",\n", $arr);

            return ($this->compiler->template($this->name, 'searchable', [
                'value' => $content
            ]));
        }
        return '';
    }
}