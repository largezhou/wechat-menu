<?php

namespace Largezhou\WechatMenu;

class WechatMenu
{
    public static function renderMenus()
    {
        return file_get_contents(__DIR__.'/../resources/views/menus.html');
    }
}
