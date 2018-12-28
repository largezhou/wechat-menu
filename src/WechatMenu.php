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
    protected static function response($html)
    {
        return Config::renderPrefixEl().$html;
    }

    /**
     * 返回编辑菜单的 html 页面内容
     *
     * @return string
     */
    public static function renderMenusEditor()
    {
        return static::response(file_get_contents(__DIR__.'/../resources/views/menus.html'));
    }
}
