<?php

if (!function_exists('safe_json_decode')) {
    /**
     * 安全解析 json，可指定解析失败时的默认值
     *
     * @param string $json
     * @param mixed  $default json 解析失败时的默认值
     *
     * @return mixed|null
     */
    function safe_json_decode(string $json, $default = null)
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $default;
        }

        return $data;
    }
}

if (!function_exists('camelize')) {
    /**
     * 把用指定分隔符分割的字符串，转成驼峰形式
     *
     * @param string $str 要被转换的字符串
     * @param string $sep 指定分隔符
     *
     * @return string 转成驼峰格式后的字符串
     */
    function camelize(string $str, string $sep = '_'): string
    {
        $str = $sep.str_replace($sep, ' ', strtolower($str));

        return ltrim(str_replace(' ', '', ucwords($str)), $sep);
    }
}
