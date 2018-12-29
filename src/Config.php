<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Kernel\Support\Arr;
use Largezhou\WechatMenu\Exceptions\WechatMenuException;

class Config
{
    protected static $routePrefix = 'wechat-menu';

    protected static $config = [];

    protected static $eventsCallbacks = [];

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

    /**
     * 获取微信配置
     *
     * @return array
     */
    public static function getWechatConfig(): array
    {
        return static::$config;
    }

    /**
     * 设置事件对应的回调
     *
     * @param array $events
     *
     * @throws WechatMenuException
     */
    public static function setEventsCallbacks(array $events)
    {
        static::validEventsCallbacks($events);

        static::$eventsCallbacks = $events;
    }

    /**
     * 获取事件对应回调的数组
     *
     * @return array
     */
    public static function getEventsCallbacks(): array
    {
        return static::$eventsCallbacks;
    }

    /**
     * 验证事件中的 key 不为空，且唯一
     *
     * @param array $events
     *
     * @throws WechatMenuException
     */
    public static function validEventsCallbacks(array $events)
    {
        $keys = [];

        foreach ($events as $e) {
            if (!isset($e['key']) || $e['key'] === '') {
                throw new WechatMenuException('事件 key 不能为空');
            }

            $key = $e['key'];
            if (in_array($key, $keys)) {
                throw new WechatMenuException("事件 key[{$key}] 不唯一");
            }

            $keys[] = $key;
        }
    }

    /**
     * 设置配置数据
     *
     * @param array $config
     */
    public static function config(array $config)
    {
        static::$config = $config;
    }

    /**
     * 单独设置某个配置
     *
     * @param  array|string $key
     * @param  mixed        $value
     */
    public static function set($key, $value = null)
    {
        if (func_num_args() == 1) {
            static::$config = array_replace_recursive(static::$config, $key);

            return;
        }

        Arr::set(static::$config, $key, $value);
    }

    /**
     * 获取配置
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return array
     */
    public static function get(string $key = null, $default = null)
    {
        if (func_num_args() == 0) {
            return static::$config;
        } else {
            return Arr::get(static::$config, $key, $default);
        }
    }
}
