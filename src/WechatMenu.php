<?php

namespace Largezhou\WechatMenu;

class WechatMenu
{
    /**
     * 返回页面内容前，加上请求地址前缀的元素
     *
     * @param $html
     *
     * @return string
     */
    protected static function wrapHtml($html)
    {
        return Config::renderPrefixEl()
            .'<link rel="stylesheet" href="/vendor/wechat-menu/app.css">'
            .'<div id="wechat-menu">'
            .$html
            .'</div>'
            .'<script src="/vendor/wechat-menu/app.js"></script>';
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
    public static function renderEventSetting()
    {
        return static::wrapHtml('<events-setting/>');
    }
}
