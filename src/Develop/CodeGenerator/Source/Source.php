<?php

namespace Longway\Frame\Develop\CodeGenerator\Source;

class Source implements \ArrayAccess,\Iterator
{
    protected $result = [];
    protected $dir;

    protected $position = 0;

    public function __construct()
    {
        $dir = env('CODE_SOURCE_DIR');

        if ( !file_exists($dir) ) {
            throw new SourceException('未找到源文件目录');
        }
        $this->dir = $dir;
        $dir = new \DirectoryIterator($dir);

        foreach ( $dir as $item ) {
            $filename = $item->getFilename();

            if ( $filename != '.' && $filename != '..' ) {
                $fileInfo = explode('.', $filename);
                if ( count($fileInfo) == 2 ) {
                    $filename = join('.', explode('_', $fileInfo[0]));
                    $this->result[] = $filename;
                }
            }
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        throw new SourceException('不允许更改');
    }

    public function offsetGet($offset)
    {
        if ( $this->offsetExists($offset) ) {
            return $this->result[$offset];
        }
        return null;
    }

    public function offsetUnset($offset)
    {
        if ( $this->offsetExists($offset) ) {
            unset($this->result[$offset]);
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->result[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return $this->offsetExists($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function getFilename($name)
    {
        if ( in_array($name, $this->result) ) {
            $arr = explode('.', $name);
            $filename = $this->dir.'/'.join('_', $arr).'.conf';
            return $filename;
        }
        return null;
    }

    public function toArray()
    {
        return $this->result;
    }
}