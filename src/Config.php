<?php

namespace Largezhou\WechatMenu;

class Config
{
    protected static $routePrefix = 'wechat-menu';

    protected static $config = [];

    /**
     * 设置系列路由的前缀
     *
     * @param string $prefix
     */
    public static function setRoutePrefix(string $prefix)
    {
        static::$routePrefix = $prefix;
    }

    /**
     * 返回用来设置前端 axios 请求的 baseURL 元素
     *
     * @return string
     */
    public static function renderPrefixEl(): string
    {
        return '<div id="wechat-menu-prefix" data-prefix="'.static::$routePrefix.'"></div>';
    }

    /**
     * 设置微信公众号的配置
     *
     * @param array $config
     */
    public static function setWechatConfig(array $config)
    {
        static::$config = $config;
    }

    public static function getWechatConfig(): array
    {
        return static::$config;
    }
}
