<?php

namespace Largezhou\WechatMenu;

class Data
{
    public static function getMenus()
    {
        return WechatApp::getApp()->menu->list();
    }

    public static function updateMenus($menus)
    {
        return WechatApp::getApp()->menu->create($menus);
    }
}
