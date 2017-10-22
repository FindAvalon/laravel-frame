<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;


abstract class Base
{
    protected $data;
    protected $info;
    protected $name;

    public function __construct($info, $data)
    {
        $this->data = $data;
        $this->info = $info;
    }

    abstract public function parse();

    /**
     * 获取指定模版文件转换后的内容
     * @param $name
     * @param $params
     * @return mixed
     */
    public function template($name, $params)
    {
        $templateFile = __DIR__.'/../Template/'.ucfirst($this->name).'/'.$name.'.tpl';
        $content = file_get_contents($templateFile);
        $keys = array_map(function ($key) {
            return '{{'.$key.'}}';
        }, array_keys($params));
        $values = array_values($params);
        return str_replace($keys, $values, $content);
    }
}