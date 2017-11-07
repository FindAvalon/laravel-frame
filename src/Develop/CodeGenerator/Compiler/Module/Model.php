<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;

use Longway\Frame\Develop\CodeGenerator\Compiler\Compiler;

class Model implements ModuleInterface
{
    use ModuleTrait;

    const NAMESPACE_PREFIX = 'App\\Models';

    protected $name = 'model';

    public function parse(Compiler $compiler, $data)
    {
        $this->data = $data;
        $this->compiler = $compiler;

        $result = [
            'fillable'      => $this->fillable(),
            'scope'         => $this->scope(),
            'belongs_to'    => $this->belongsTo()
        ];
        $result = array_merge($result, $compiler->info);
        $globalSign = $result['global'] ?? false;
        unset($result['global']);

        if ( !$globalSign ) {
            $result['namespace'] = empty($result['namespace']) ? self::NAMESPACE_PREFIX : self::NAMESPACE_PREFIX.$result['namespace'];
        }
        $content = $compiler->template($this->name, 'index', $result);
        if ( $dir = $this->compiler->getDir($result['namespace']) ) {
            if ( !file_exists($dir) ) mkdir($dir);
            $filename = $result['name'].'.php';
            file_put_contents($dir."/" .$filename, $content);
            $this->compiler->moduleInfo['model'] = [
                'namespace' => $result['namespace'],
                'name'      => $result['name']
            ];
        }

    }

    protected function fillable()
    {
        if ( $data = $this->compiler->getParam($this->data, 'fillable') ) {
            $value = join("','", $data);
            return ($this->compiler->template($this->name, 'fillable', [
                'value' => "'".$value."'"
            ]));
        }
        return '';
    }

    protected function scope()
    {
        $content = '';
        if ( $data = $this->compiler->getParam($this->data, 'scope') ) {
            foreach ( $data as $key => $item ) {
                $paramStr = '';
                if ( isset($item['params']) ) {
                    foreach ( $item['params'] as $paramName => $param ) {
                        $paramArr[] = $param[0].' $'.$paramName;
                    }
                    $paramStr = ', '.join(', ', $paramArr);
                }

                $content .= $this->compiler->template('scope', [
                    'name'      => ucfirst(camel_case($key)),
                    'params'    => $paramStr,
                    'code'      => $item['code']
                ]);
            }
            return $content;
        }
        return '';
    }

    public function belongsTo()
    {
        $content = '';
        if ( $data = $this->compiler->getParam($this->data, 'belongs_to') ) {
            foreach ( $data['params'] as $key => $item ) {
                $class = $this->getNamespacePrefix()."\\".join("\\", array_map(function ($v) {
                    return ucfirst($v);
                }, explode('.', $item[0]))).'::class';
                $content .= $this->compiler->template('belongs_to', [
                    'name'          => $key,
                    'class'         => $class,
                    'foreign_key'   => $item[1],
                    'primary_key'   => $item[2]
                ]);
            }
            return $content;
        }
    }

    public function ext()
    {
        $content = '';

        if ( $data = $this->data['ext'] ) {

        }
    }

    protected function getNamespacePrefix()
    {
        return self::NAMESPACE_PREFIX;
    }
}