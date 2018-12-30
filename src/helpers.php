<?php

if (!function_exists('safe_json')) {
    /**
     * 安全解析 json，可指定解析失败时的默认值
     *
     * @param string $json
     * @param mixed  $default json 解析失败时的默认值
     *
     * @return mixed|null
     */
    function safe_json(string $json, $default = null)
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $default;
        }

        return $data;
    }
}
