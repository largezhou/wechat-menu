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
