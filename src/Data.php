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
     * @return string
     * @throws Exceptions\WechatMenuException
     */
    public static function getMenus()
    {
        return static::success('', Manager::getInstance()->getWechat()->menu->list());
    }

    /**
     * 创建公众号菜单
     *
     * @param array $menus
     *
     * @return string
     * @throws Exceptions\WechatMenuException
     */
    public static function createMenus(array $menus)
    {
        $res = Manager::getInstance()->getWechat()->menu->create($menus);

        if ($res['errcode'] == 0) {
            return Data::success('菜单保存成功');
        } else {
            return Data::error("[{$res['errcode']}] {$res['errmsg']}");
        }
    }

    /**
     * 获取事件配置
     *
     * @param array $events
     *
     * @return string
     */
    public static function getEvents(array $events = null)
    {
        if (!$events) {
            $events = static::getData('events');
        }

        return Data::success('', $events);
    }

    /**
     * 存储事件配置
     *
     * @param array $events
     *
     * @return string
     */
    public static function createEvents(array $events)
    {
        $data = static::getData();
        $data['events'] = $events;
        $data = json_encode($data, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);

        file_put_contents(Manager::getInstance()->getConfig('data_path'), $data);

        return Data::success('保存设置成功');
    }

    /**
     * 返回成功
     *
     * @param string $msg 消息
     * @param string $data 附带数据
     *
     * @return string
     */
    public static function success($msg = '', $data = null)
    {
        return json_encode(
            [
                'status' => true,
                'msg' => $msg,
                'data' => $data,
            ]
        );
    }

    /**
     * 返回错误
     *
     * @param string $msg 消息
     * @param string $data 附带数据
     *
     * @return string
     */
    public static function error($msg = '', $data = null)
    {
        return json_encode(
            [
                'status' => false,
                'msg' => $msg,
                'data' => $data,
            ]
        );
    }
}
