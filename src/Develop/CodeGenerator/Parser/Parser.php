<?php

namespace Longway\Frame\Develop\CodeGenerator\Parser;

class Parser implements ParserInterface
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
        $originalContent = $content;
        $content = $this->clearCode($content);
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

                $str = substr($originalContent, $subStartIndex, $subLength);
                $content = $this->strOnceReplace($content, $startIndex, $endIndex);
                $originalContent = $this->strOnceReplace($originalContent, $startIndex, $endIndex);

                if ( isset($data[$key]) ) {
                    $data[$key.'@1'] = $data[$key];
                    unset($data[$key]);
                    $key .= '@2';
                } else {
                    $keys = array_keys($data);
                    $newData = array_filter($keys, function ($item) use ($key) {
                        if ( strpos($item, $key.'@') ) {
                            return true;
                        }
                        return false;
                    });
                    $num = count($newData);

                    if ( $num > 0 ) {
                        $key = $key.'@'.($num + 1);
                    }
                }

                $data[$key] = $this->parseTop($str);
            }

        } else {
            return $this->parseParam($originalContent);
        }
        $content = trim(substr($content, 1, strlen($content) - 2));
//        $originalContent = trim(substr($originalContent, 1, strlen($originalContent) - 2));
        $data = array_merge($data, $this->parseParam($content));

        return $data;
    }

    protected function parseParam($content)
    {
        $content = trim($content);
        if ( !$content ) {
            return [];
        }
        $data = [
        ];

        preg_match_all('/@[\w~.\s]+;/U', $content, $result);

        if ( $result && count($result[0]) > 0 ) {
            foreach ( $result[0] as $value ) {
                if ( empty($value) ) {
                    continue;
                }
                $value = trim($value, ' @;');

                if ( $index = strpos($value, ' ') ) {
                    $key = substr($value, 0, $index);
                    $value = substr($value, $index + 1, strlen($value) - $index);
                    $valueArr = explode(' ', $value);

                    $tempArr = [];
                    foreach ( $valueArr as $v ) {
                        $temp = explode(':', $v);
                        if ( count($temp) == 2 ) $tempArr[$temp[0]] = $temp[1];
                        else $tempArr[] = $v;
                    }
                    $data['params'][$key] = $tempArr;
                } else {
                    throw new ParserException('格式错误：'.$value);
                }
            }
        }


        preg_match_all('/```[\w\W]+```/', $content, $result);

        if ( $result && count($result[0]) > 0 ) {
            $result = $result[0];

            foreach ( $result as $value ) {
                if ( empty($value) ) {
                    continue;
                }
                $value = trim($value, " `\n");
                $data['code'] = $value;
            }
        }

        return $data;
    }

    protected function clearCode($content)
    {
        $startIndex = strpos($content, '```');

        if ( !$startIndex ) {
            return $content;
        }

        $endIndex = strpos($content, '```', $startIndex + 1);

        $search = substr($content, $startIndex, $endIndex + 3 - $startIndex);

        if ( !$search )
            return $content;

        $content = str_replace($search, join('', array_fill(0, strlen($search), ' ')), $content);
        return $this->clearCode($content);
    }

    private function strOnceReplace($content, $startIndex, $endIndex)
    {
        $left = substr($content, 0, $startIndex);
        $right = substr($content, $endIndex + 1, strlen($content) - $endIndex);
        return $left.$right;
    }
}