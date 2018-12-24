<?php

namespace Largezhou\WechatMenu;

class Data
{
    public static function getMenus()
    {
        return WechatApp::getApp()->menu->list();
    }
}
