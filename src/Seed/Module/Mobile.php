<?php

namespace Longway\Frame\Seed\Module;

class Mobile implements ModuleInterface
{
    public function make(array $data)
    {
        $prefix = [
            '13', '14', '15', '17', '18'
        ];
        $index = mt_rand(0, count($prefix) - 1);
        $mobile = $prefix[$index];

        for ( $i = 0; $i < 9; $i++ ) {
            $mobile .= mt_rand(0, 9);
        }

        return $mobile;
    }
}