<?php

namespace Largezhou\WechatMenu;

class Content
{
    const RESOURCES = [
        '/app.js',
        '/app.css',
    ];

    /**
     * 返回用来设置前端 axios 请求的 baseURL 元素
     *
     * @return string
     */
    public static function renderPrefixEl(): string
    {
        $prefix = Manager::getInstance()->getConfig('routePrefix');

        return '<div id="wechat-menu-prefix" data-prefix="'.$prefix.'"></div>';
    }

    protected static function getVersionFromManifest()
    {
        $defaultMap = array_combine(static::RESOURCES, static::RESOURCES);

        return safe_json(file_get_contents(__DIR__.'/../resources/mix-manifest.json'), $defaultMap);
    }

    /**
     * 返回页面内容前，加上请求地址前缀的元素
     *
     * @param $html
     *
     * @return string
     */
    protected static function wrapHtml($html)
    {
        $versionMap = static::getVersionFromManifest();

        return static::renderPrefixEl()
            .'<link rel="stylesheet" href="/vendor/wechat-menu'.$versionMap['/app.css'].'">'
            .'<div id="wechat-menu">'
            .$html
            .'</div>'
            .'<script src="/vendor/wechat-menu'.$versionMap['/app.js'].'"></script>';
    }

    /**
     * 返回编辑菜单的 html 页面内容
     *
     * @return string
     */
    public static function renderMenusEditor()
    {
        return static::wrapHtml('<menus-editor/>');
    }

    /**
     * 返回事件标识对应时间处理函数的 增删改查 页面
     *
     * @return string
     */
    public static function renderMenuEventSetting()
    {
        return static::wrapHtml('<menu-events-setting/>');
    }

    public static function renderOtherEventsSetting()
    {
        return static::wrapHtml('<other-events-setting/>');
    }
}
