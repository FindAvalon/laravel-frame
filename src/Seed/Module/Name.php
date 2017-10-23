<?php

namespace Longway\Frame\Seed\Module;

class Name implements ModuleInterface
{
    protected $result;

    public function __construct($result = null)
    {
        if ( $result ) {
            $this->result = $result;
        } else {
            $this->result = explode("\n", file_get_contents(__DIR__.'/../data/name.txt'));
//            $this->result = array_filter($this->result, function ($item) {
//                $len = mb_strlen($item);
//                return $len == 2 || $len == 3 || $len == 4;
//            });
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function make(array $data)
    {
        $index = mt_rand(0, count($this->result) - 1);

        return $this->result[$index];
    }
}