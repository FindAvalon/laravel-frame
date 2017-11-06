<?php

namespace Longway\Frame\Develop\CodeGenerator\Compiler\Module;


use Longway\Frame\Develop\CodeGenerator\Compiler\Compiler;

interface ModuleInterface
{
    public function parse(Compiler $compiler, $data);
}