<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;

class Model extends Base
{
    const DIR = 'Models';
    const NAMESPACE_PREFIX = 'App\\Models';

    protected $name = 'model';

    public function parse()
    {
        $result = [
            'fillable'      => $this->fillable(),
            'scope'         => $this->scope(),
            'belongs_to'    => $this->belongsTo()
        ];
        $result = array_merge($result, $this->info);
        $content = $this->template('index', $result);

        $path = $this->getDir().str_replace("\\", '/', $this->info['namespace']);
        $filename = $this->info['name'].'.php';
        if ( !file_exists($path) ) {
            mkdir($path);
        }
        file_put_contents($path."/".$filename, $content);

    }

    protected function fillable()
    {
        if ( $data = $this->data['params']['fillable'] ) {
            $value = join("','", $data);
            return ($this->template('fillable', [
                'value' => "'".$value."'"
            ]));
        }
        return '';
    }

    protected function scope()
    {
        $content = '';
        if ( $data = $this->data['scope'] ) {
            foreach ( $data as $key => $item ) {
                $paramStr = '';
                if ( isset($item['params']) ) {
                    foreach ( $item['params'] as $paramName => $param ) {
                        $paramArr[] = $param[0].' $'.$paramName;
                    }
                    $paramStr = ', '.join(', ', $paramArr);
                }

                $content .= $this->template('scope', [
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
        if ( $data = $this->data['belongs_to'] ) {
            foreach ( $data['params'] as $key => $item ) {
                $class = $this->getNamespacePrefix()."\\".join("\\", array_map(function ($v) {
                    return ucfirst($v);
                }, explode('.', $item[0]))).'::class';
                $content .= $this->template('belongs_to', [
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

    protected function getDir()
    {
        return app_path().'/'.self::DIR;
    }

    protected function getNamespacePrefix()
    {
        return self::NAMESPACE_PREFIX;
    }
}