<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler;

use Longway\Frame\Develop\CodeGenerator\Compiler\Module\Model;
use Longway\Frame\Develop\CodeGenerator\Compiler\Module\ModuleInterface;

class Compiler
{
    public $info;

    protected $modules = [];

    protected $namespaces = [];

    public function __construct()
    {
        $this->namespaces = array_merge(
            include_once app('path').'/../vendor/composer/autoload_namespaces.php',
            include_once app('path').'/../vendor/composer/autoload_psr4.php',
            include_once app('path').'/../vendor/composer/autoload_classmap.php'
        );
    }

    public function addModule($alias, ModuleInterface $module)
    {
        $this->modules[$alias] = $module;
    }

    public function exec($data)
    {
        if ( !isset($data['params']['name']) ) {
            throw new CompilerException('请定义name');
        }

        $this->info = $this->analysisName(array_pop($data['params']['name']));

        foreach ( $this->modules as $alias => $module ) {
            if ( isset($data[$alias]) ) {
                $module->parse($this, $data[$alias]);
            }
        }
    }

    /**
     * 解析名称
     * @param $name 名称（group.user）
     * @return array
     */
    public function analysisName($name)
    {
        $result = explode('.', $name);
        $result = array_map(function ($item) {
            return ucfirst(strtolower($item));
        }, $result);

        $name = array_pop($result);
        $namespace = $result ? "\\".join("\\", $result) : '';

        return [
            'name'      => $name,
            'namespace' => $namespace
        ];
    }

    /**
     * 获取指定模版文件转换后的内容
     * @param $name
     * @param $params
     * @return mixed
     */
    public function template($moduleName, $name, $params)
    {
        $templateFile = __DIR__.'/Template/'.ucfirst($moduleName).'/'.$name.'.tpl';
        $content = file_get_contents($templateFile);
        $keys = array_map(function ($key) {
            return '{{'.$key.'}}';
        }, array_keys($params));
        $values = array_values($params);
        return str_replace($keys, $values, $content);
    }

    public function getNamespace($namespace)
    {
        if ( isset($this->namespaces[$namespace]) ) {
            return $this->namespaces[$namespace];
        }
        return null;
    }

    public function getParam($data, $key)
    {
        if ( isset($data['params']) && isset($data['params'][$key]) ) {
            return $data['params'][$key];
        }
        return null;
    }
}