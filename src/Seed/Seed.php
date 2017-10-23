<?php

namespace Longway\Frame\Seed;

use Longway\Frame\Seed\Module\ModuleInterface;
use Closure;

/**
 * Class Seed
 * Module 姓名 手机 数字 邮箱
 * @package Longway\Frame\Seed
 */
class Seed
{
    protected $data = [];

    public function addFiled($name, ModuleInterface $module, Closure $closure = null)
    {
        $this->data[$name] = [
            'module'    => $module,
            'closure'   => $closure
        ];
    }

    public function exec(int $amount = 1)
    {
        $result = [];
        for ( $i = 0; $i < $amount; $i++ ) {
            foreach ( $this->data as $name => $item ) {
                if ( $item['closure'] ) {
                    $result[$i][$name] = call_user_func_array($item['closure'], [$result[$i] ?? []]);
                } else {
                    $result[$i][$name] = $item['module']->make($result[$i] ?? []);
                }
            }
        }
        return $result;
    }
}