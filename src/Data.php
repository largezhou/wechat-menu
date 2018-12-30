<?php

namespace Largezhou\WechatMenu;

use Largezhou\WechatMenu\Exceptions\WechatMenuException;

class Data
{
    /**
     * 获取存储的数据
     *
     * @param string|null $type
     *
     * @return array|mixed|null
     */
    public static function getData(string $type = null)
    {
        $data = safe_json(file_get_contents(Manager::getInstance()->getConfig('data_path')), []);

        if (!$type) {
            return $data;
        } else {
            return $data[$type] ?? [];
        }
    }

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
     * 获取事件配置
     *
     * @param array $events
     *
     * @return array
     */
    public static function getEvents(array $events = null)
    {
        if (!$events) {
            $events = static::getData('events');
        }

        return $events;
    }

    /**
     * 存储事件配置
     *
     * @param array $events
     */
    public static function createEvents(array $events)
    {
        $data = static::getData();
        $data['events'] = $events;
        $data = json_encode($data, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);

        file_put_contents(Manager::getInstance()->getConfig('data_path'), $data);
    }
}
