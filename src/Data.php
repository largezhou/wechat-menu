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
     * @param array $menus
     *
     * @return mixed
     * @throws Exceptions\WechatMenuException
     */
    public static function createMenus(array $menus)
    {
        return WechatApp::getApp()->menu->create($menus);
    }

    /**
     * 获取事件 key => callback 键值对，如：
     * [
     *     [
     *         'key' => 'TEST_1',
     *         'type' => 'callback',
     *         'content' => 'Path\To\Class@methodOne',
     *     ],
     *     [
     *         'key' => 'TEST_2',
     *         'type' => 'msg',
     *         'content' => 'Hello World',
     *     ],
     * ]
     *
     * @param array|string $events
     *
     * @return array|string
     */
    public static function getEvents($events)
    {
        return $events;
    }

    /**
     * @param array $events
     */
    public static function createEvents(array $events)
    {

    }
}
