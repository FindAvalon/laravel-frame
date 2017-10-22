<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler;

use Longway\Frame\Develop\CodeGenerator\Compiler\Module\Model;

class Compiler
{
    public function exec($data)
    {
        if ( !isset($data['params']['name']) ) {
            throw new CompilerException('请定义name');
        }

        $info = $this->analysisName(array_pop($data['params']['name']));

        if ( isset($data['model']) ) {
            $model = new Model($info, $data['model']);
            $model->parse();
        }
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

        $name = array_pop($result);
        $namespace = $result ? "\\".join("\\", $result) : '';

        return [
            'name'      => $name,
            'namespace' => $namespace
        ];
    }
}