<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Factory;
use Largezhou\WechatMenu\Exceptions\WechatMenuException;

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
}
