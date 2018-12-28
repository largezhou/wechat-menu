<?php

namespace Largezhou\WechatMenu;

class WechatMenu
{
    /**
     * 返回编辑菜单的 html 页面内容
     *
     * @return string
     */
    public static function renderMenus()
    {
        return Config::renderPrefixEl().file_get_contents(__DIR__.'/../resources/views/menus.html');
    }
}
