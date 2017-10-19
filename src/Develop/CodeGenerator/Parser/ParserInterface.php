<?php

namespace Longway\Frame\Develop\CodeGenerator\Parser;

interface ParserInterface
{
    public function load($filename);

    public function exec($content);
}