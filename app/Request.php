<?php
namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    /**
     * 不过滤变量名
     * @var array
     */
    protected $except = [];

    public function testfn()
    {
        dump('testfnbbbbbbbbb');
    }

    /**
     * 获取请求的数据
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function more(array $params = [], bool $suffix = true, bool $filter = true): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $this->param($param);
            } else {
                if (!isset($param[1])) {
                    $param[1] = null;
                }

                if (!isset($param[2])) {
                    $param[2] = '';
                }

                if (is_array($param[0])) {
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }

                $p[$suffix == true ? $i++ : ($param[3] ?? $keyName)] = $this->param($name, $param[1], $param[2]);
            }
        }

        if ($filter && $p) {
            $p = $this->filterArrayValues($p);
        }

        return $p;
    }

    /**
     * 过滤接数组中的字符串
     * @param $str
     * @param bool $filter
     * @return array|mixed|string|string[]
     */
    public function filterArrayValues($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // 如果值是数组，并且不在不过滤变量名里面，递归调用 filterArrayValues，否则直接赋值
                $result[$key] = in_array($key, $this->except) ? $value : $this->filterArrayValues($value);
            } else {
                if (in_array($key, $this->except) || is_int($value) || is_null($value)) {
                    $result[$key] = $value;
                } else {
                    // 如果值是字符串，过滤特殊字符
                    $result[$key] = filter_str($value);
                }

            }
        }
        return $result;
    }

    /**
     * 获取get参数
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function getMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }
}
