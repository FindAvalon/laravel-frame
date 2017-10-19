<?php

namespace Longway\Laravel\Frame\Develop;

class Service
{
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