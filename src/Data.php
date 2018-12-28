<?php

namespace Largezhou\WechatMenu;

class Data
{
    /**
     * 返回从微信服务器获取的公众号菜单
     *
     * @return mixed
     * @throws Exceptions\WechatMenuException
     */
    public static function getMenus()
    {
        return WechatApp::getApp()->menu->list();
    }

    /**
     * 创建公众号菜单
     *
     * @param $menus
     *
     * @return mixed
     * @throws Exceptions\WechatMenuException
     */
    public static function createMenus($menus)
    {
        return WechatApp::getApp()->menu->create($menus);
    }
}
