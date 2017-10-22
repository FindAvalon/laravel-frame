<?php

namespace Longway\Frame\Develop\CodeGenerator\Cache;

use Storage;
use File;

class Cache
{
    const CACHE_PATH = 'frame/';

    public function set($filename)
    {
        $content = file_get_contents($filename);

        $storage = Storage::disk('local');
        $cacheIndexFilename = self::CACHE_PATH.md5($filename);

        if ( !$storage->exists($cacheIndexFilename) ) {
            $cacheFilename = md5($filename.'@1');
            $storage->put($cacheIndexFilename, json_encode([
                $cacheFilename
            ]));
            $storage->put(self::CACHE_PATH.$cacheFilename, $content);
        } else {
            $indexArr = json_decode($storage->get($cacheIndexFilename), true);
            $indexArrLen = count($indexArr);
            if ( $indexArrLen > 0 ) {
                $cacheContent = $storage->get(self::CACHE_PATH.$indexArr[$indexArrLen - 1]);

                if ( $cacheContent != $content ) {
                    $cacheFilename = md5($filename.'@'.$indexArrLen + 1);
                    $storage->put(self::CACHE_PATH.$cacheFilename, $content);
                    $indexArr[] = $cacheFilename;
                    $storage->put($cacheIndexFilename, json_encode($indexArr));
                }
            }
        }
    }

    public function get($filename)
    {
        $cacheIndexFilename = self::CACHE_PATH.md5($filename);

        $storage = Storage::disk('local');
        $indexArr = json_decode($storage->get($cacheIndexFilename), true);
        rsort($indexArr);

        $data = [];
        foreach ( $indexArr as $item ) {
            $time = File::lastModified(storage_path().'/app/'.self::CACHE_PATH.$item);
            $data[] = [
                'date'      => date('Y-m-d H:i:s', $time),
                'filename'  => $item
            ];
        }
        return $data;
    }

    public function clear()
    {

    }
}