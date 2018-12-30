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
        return Manager::getInstance()->getWechat()->menu->list();
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
        return Manager::getInstance()->getWechat()->menu->create($menus);
    }

    /**
     * 获取事件 key => callback 键值对，如：
     *
     * @param array $events
     *
     * @return array
     */
    public static function getEvents(array $events = null)
    {
        if (!$events) {
            $events = Manager::getInstance()->getConfig('eventsCallbacks');
        }

        return $events;
    }

    /**
     * @param array $events
     */
    public static function createEvents(array $events)
    {

    }
}
