<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Message;
use Largezhou\WechatMenu\Exceptions\WechatMenuException;
use Largezhou\WechatMenu\Handlers\EventHandler;

class WechatApp
{
    protected static $app;

    /**
     * 获取公众号管理实例
     *
     * @return \EasyWeChat\OfficialAccount\Application
     * @throws WechatMenuException
     */
    public static function getApp()
    {
        $config = Config::getWechatConfig();

        if (empty($config)) {
            throw new WechatMenuException('还没有设置公众号');
        }

        if (!static::$app) {
            static::$app = Factory::officialAccount(Config::getWechatConfig());
        }

        return static::$app;
    }

    /**
     * 公众号回调接口
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws WechatMenuException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public static function serve()
    {
        $server = static::getApp()->server;
        $server->push(EventHandler::class, Message::EVENT);

        return $server->serve();
    }
}
