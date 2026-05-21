<?php
declare (strict_types = 1);

namespace app\utils;

class FormatArray
{
    /**
     * 线路加上拼音分类
     * @param array $items 原始数据
     * @param string $childrenKey 子节点字段名
     * @return array
     */
    public static function withPinyin(array $items): array
    {
        foreach ($items as $key => $value) {
            switch ($value['type_id']) {
                case 1:
                    $items[$key]['pinyin'] = 'zhoubian';
                    break;
                case 2:
                    $items[$key]['pinyin'] = 'guonei';
                    break;
                case 3:
                    $items[$key]['pinyin'] = 'chujing';
                    break;
            }
        }
        return $items;
    }

    //截取字符串
    public static function msubstr(string $str, int $length, $start = 0, $charset = "utf-8", $suffix = true)
    {
        $str = trim(strip_tags($str));
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
            if (false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        return strlen($str) > $length * 3 ? $slice . '...' : $slice;
    }
}
