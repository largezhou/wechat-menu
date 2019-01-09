<?php

namespace Largezhou\WechatMenu;

use Largezhou\WechatMenu\Exceptions\WechatMenuException;

class Data
{
    /**
     * 允许的请求类型.
     */
    const REQUEST_TYPES = [
        'menus',
        'menu_events',
        'other_events',
    ];

    /**
     * 返回成功.
     *
     * @param string $msg  消息
     * @param string $data 附带数据
     *
     * @return string
     */
    public static function success($msg = '', $data = null): string
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
     * 返回错误.
     *
     * @param string $msg  消息
     * @param string $data 附带数据
     *
     * @return string
     */
    public static function error($msg = '', $data = null): string
    {
        return json_encode(
            [
                'status' => false,
                'msg' => $msg,
                'data' => $data,
            ]
        );
    }

    /**
     * 获取存储的数据.
     *
     * @param string|null $type
     *
     * @return array|mixed|null
     */
    public static function getData(string $type = null)
    {
        $data = safe_json_decode(@file_get_contents(Manager::getInstance()->getConfig('data_path')), []);

        if (!$type) {
            return $data;
        } else {
            return $data[$type] ?? [];
        }
    }

    /**
     * 存储数据.
     *
     * @param mixed $allData 需要保存的数据
     */
    public static function saveData($allData)
    {
        file_put_contents(Manager::getInstance()->getConfig('data_path'), $allData);
    }

    /**
     * 返回从微信服务器获取的公众号菜单.
     *
     * @return string
     *
     * @throws Exceptions\WechatMenuException
     */
    public static function getMenus(): string
    {
        return static::success('', Manager::getInstance()->getWechat()->menu->list());
    }

    /**
     * 创建公众号菜单.
     *
     * @param array $menus
     *
     * @return string
     *
     * @throws Exceptions\WechatMenuException
     */
    public static function postMenus(array $menus): string
    {
        if (empty($menus)) {
            return static::error('至少要有一个菜单才能保存');
        }

        $res = Manager::getInstance()->getWechat()->menu->create($menus);

        if ($res['errcode'] == 0) {
            return static::success('菜单保存成功');
        } else {
            return static::error("[{$res['errcode']}] {$res['errmsg']}");
        }
    }

    /**
     * 获取指定类型的数据.
     *
     * @param array $data 请求中的数据
     *
     * @return string json_encode 后的数据
     *
     * @throws WechatMenuException
     */
    public static function getResources(array $data): string
    {
        $type = static::checkAndGetType($data);

        if ($type == 'menus') {
            return static::getMenus();
        } else {
            return static::getSettings($type);
        }
    }

    /**
     * 存储指定类型的数据.
     *
     * @param array $data
     *
     * @return string
     *
     * @throws WechatMenuException
     */
    public static function postResources(array $data): string
    {
        $type = static::checkAndGetType($data);

        if ($type == 'menus') {
            return static::postMenus($data['data'] ?? null);
        } else {
            return static::postSettings($type, $data['data'] ?? null);
        }
    }

    /**
     * 获取指定键的数据.
     *
     * @param string $type
     *
     * @return string
     */
    public static function getSettings($type): string
    {
        return static::success('', static::getData($type));
    }

    /**
     * 保存来自请求的数据.
     *
     * @param string $type
     * @param mixed  $data
     *
     * @return string
     */
    public static function postSettings(string $type, $data): string
    {
        $allData = static::getData();
        $allData[$type] = $data;
        $allData = json_encode($allData, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);

        static::saveData($allData);

        return static::success('保存设置成功');
    }

    /**
     * 检测请求中的 type 值，并返回.
     *
     * @param array $data 请求中的数据
     *
     * @return string
     *
     * @throws WechatMenuException
     */
    protected static function checkAndGetType(array $data): string
    {
        $type = trim($data['type'] ?? '');

        if (!$type) {
            throw new WechatMenuException('请求中 type 不能为空');
        }

        if (!in_array($type, static::REQUEST_TYPES)) {
            throw new WechatMenuException('请求中 type 参数不正确');
        }

        return $type;
    }
}
