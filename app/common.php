<?php
/**
 * 错误返回值函数
 * @param int $code
 * @param string $message
 * @param string $data
 * @return array
 */
function error($code = -1, $message = '', $data = '')
{
    return [
        'errCode' => $code,
        'errMsg' => $message,
        'data' => $data,
    ];
}

/**
 * 返回值函数
 * @param int $code
 * @param string $message
 * @param string $data
 * @return array
 */
function success($code = 0, $message = '', $data = '')
{
    return [
        'errCode' => $code,
        'errMsg' => $message,
        'data' => $data,
    ];
}

/**
 * 获取客户端IP
 * @return string
 */
function getClientIp()
{
    $onlineip = '';
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * 先获取顶级规则，再将该顶级规则下的子规则放入到对应的顶级规则下
 * @return array
 */
function getMenuSubItem($id, $data)
{
    $arr = array();
    foreach ($data as $key => $value) {
        // 循环$menus二维数组将pid=0的规则赋值给$arr，这里面都是顶级规则
        if ($value['pid'] == 0) {
            $arr[$value[$id]] = $value;
        } else {
            $arr[$value['pid']]['sub'][] = $value;
        }
    }
    return $arr;
}

/**
 * 函数：树状结构
 * @param  array $items      原始数据，需要带有id,pid字段
 * @return array           返回性别
 */
function toTree($items)
{
    $tree = array();
    $tmpMap = array();

    foreach ($items as $item) {
        $tmpMap[$item['id']] = $item;
    }

    foreach ($items as $item) {
        if (isset($tmpMap[$item['pid']])) {
            $tmpMap[$item['pid']]['children'][] = &$tmpMap[$item['id']];
        } else {
            $tree[] = &$tmpMap[$item['id']];
        }
    }
    return $tree;
}

/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
function curlPost($url, array $params = array())
{
    $data_string = json_encode($params, JSON_UNESCAPED_UNICODE);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function curlPostRaw($url, $rawData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: text',
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function curlPostMedia($url, $media)
{
    $data['media'] = $media;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curlGet($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * 生成A-Za-z0-9随机字符串
 * @param int $length 字符串长度
 * @param bool $isNumber 是否纯数字
 * @return string $str 字符串
 */
function getRandChar($length, $isNumber = false)
{
    $str = null;
    if ($isNumber) {
        $strPol = "0123456789";
    } else {
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    }
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

/**
 * 二维数组根据某个字段排序
 * @param array $array 要排序的数组
 * @param string $keys   要排序的键字段
 * @param string $sort  排序类型  SORT_ASC     SORT_DESC
 * @return array 排序后的数组
 */
function arraySort($array, $keys, $sort = SORT_DESC)
{
    $keysValue = [];
    foreach ($array as $k => $v) {
        $keysValue[$k] = $v[$keys];
    }
    array_multisort($keysValue, $sort, $array);
    return $array;
}

/**
 * 取某一天零点时间戳(本地时间)
 * @param number $time 欲获取某日的时间戳
 * return number 这天零点时间戳
 */
function getZeroTime($timestamp)
{
    // Z 时差偏移量的秒数。UTC 西边的时区偏移量总是负的，UTC 东边的时区偏移量总是正的。
    $timezone = date('Z', $timestamp);
    $timestamp += $timezone;
    $time = $timestamp - $timestamp % 86400 - $timezone;
    return $time;
}

/**
 * 函数：格式化字节大小
 * @param  int $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function formatBytes($size = 0, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }

    return round($size, 2) . $delimiter . $units[$i];
}

// 随机密码生成函数
function generatePassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

// 密码加密函数
function encryptPassword($password, $salt = '')
{
    return md5(md5($password) . $salt);
}

if (!function_exists('filter_str')) {
    /**
     * 过滤字符串敏感字符
     * @param $str
     * @return array|mixed|string|string[]|null
     */
    function filter_str($str)
    {
        // $param_filter_type = sys_config('param_filter_type');
        // if ($param_filter_type != 0) {
        //     $rules = preg_split('/\r\n|\r|\n/', base64_decode(sys_config('param_filter_data')));
        //     if ($param_filter_type == 1) {
        //         foreach ($rules as $item) {
        //             if (preg_match($item, $str)) {
        //                 throw new \Exception('接口请求失败：非法操作！');
        //             }
        //         }
        //     }
        //     if (filter_var($str, FILTER_VALIDATE_URL)) {
        //         $url = parse_url($str);
        //         if (!isset($url['scheme'])) {
        //             return $str;
        //         }

        //         $host = $url['scheme'] . '://' . $url['host'];
        //         $str = $host . preg_replace($rules, '', str_replace($host, '', $str));
        //     } else {
        //         $str = preg_replace($rules, '', $str);
        //     }
        // }
        return $str;
    }
}
