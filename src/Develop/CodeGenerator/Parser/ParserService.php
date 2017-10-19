<?php

namespace Longway\Frame\Develop\CodeGenerator\Parser;


class ParserService implements ParserInterface
{
    public function load($filename)
    {
        if ( !file_exists($filename) ) {
            throw new ParserException('未找到文件');
        }
        return $this->exec(file_get_contents($filename));
    }

    public function exec($content)
    {
        return $this->parseTop($content);
    }

    protected function parseTop($content)
    {
        preg_match_all('/[a-zA-Z_][\w]* {/', $content, $result);

        $data = [];

        if ( $result && count($result[0]) > 0 ) {
            $result = $result[0];

            $count = count($result);
            for ( $i = 0; $i < $count; $i++ ) {
                $originalKey = $result[$i];
                $key = rtrim($result[$i], ' {');
                $startIndex = strpos($content, $result[$i]);

                $len = strlen($result[$i]) + 1;

                $leftIndex = strpos($content, '{', $startIndex + $len);
                $rightIndex = strpos($content, '}', $startIndex + $len);


                if ( $leftIndex > $rightIndex ) {
                    $endIndex = $rightIndex;
                } else {
                    $offset = 1;
                    do {
                        if ( !isset($result[$i + $offset]) ) {
                            $endIndex = $rightIndex;
                            $i = $i + $offset - 1;
                            break;
                        }
                        $leftIndex = strpos($content, $result[$i + $offset]);

                        if ( $leftIndex > $rightIndex || $leftIndex === false ) {
                            $endIndex = $rightIndex;
                            $searching = false;
                            $i = $i + $offset - 1;
                        } else {
                            $searching = true;
                            $offset++;
                            $rightIndex = strpos($content, '}', $rightIndex + 1);
                        }

                        if ( $offset == 10 ) {
                            $searching = false;
                        }
                    } while($searching);
                }

                $subStartIndex = $startIndex + strlen($originalKey);
                $subLength = $endIndex - $subStartIndex;

                $str = substr($content, $subStartIndex, $subLength);
                $content = $this->strOnceReplace($content, $startIndex, $endIndex);



                $data[$key] = $this->parseTop($str);
            }

        } else {
            $content = trim($content);
            if ( !$content ) {
                return null;
            }

            $values = explode(';', $content);
            $result = [];
            foreach ( $values as $value ) {

                if ( empty($value) ) {
                    continue;
                }

                if ( $index = strpos($value, ' ') ) {
                    $key = substr($value, 0, $index);
                    $value = substr($value, $index + 1, strlen($value) - $index);
                    $result[$key] = explode(',', $value);
                } else {
                    throw new Exception('格式错误：'.$value);
                }
            }
            return $result;
        }
        return $data;
    }

    private function strOnceReplace($content, $startIndex, $endIndex)
    {
        $left = substr($content, 0, $startIndex);
        $right = substr($content, $endIndex + 1, strlen($content) - $endIndex);
        return $left.$right;
    }
}