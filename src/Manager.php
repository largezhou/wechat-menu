<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Support\Arr;
use Largezhou\WechatMenu\Exceptions\WechatMenuException;
use Largezhou\WechatMenu\Handlers\EventHandler;

class Manager
{
    protected $app;
    protected $config;
    protected static $instance = null;

    private function __construct()
    {
        if (!$this->config) {
            $this->config = require __DIR__.'/./config.php';
        }
    }

    /**
     * 获取管理实例
     *
     * @return Manager
     */
    public static function getInstance(): Manager
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 获取公众号管理实例
     *
     * @return \EasyWeChat\OfficialAccount\Application
     * @throws WechatMenuException
     */
    public function getWechat()
    {
        $config = $this->getConfig('easyWechat');

        if (empty($config)) {
            throw new WechatMenuException('微信配置不能为空');
        }

        if (!$this->app) {
            $this->app = Factory::officialAccount($config);
        }

        return $this->app;
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
    public function serve()
    {
        $server = $this->getWechat()->server;
        $server->push(EventHandler::class, Message::EVENT);

        return $server->serve();
    }

    /**
     * 配置
     *
     * @param  array|string $key
     * @param  mixed        $value
     */
    public function setConfig($key, $value = null)
    {
        if (func_num_args() == 1) {
            $this->config = array_replace_recursive($this->config, $key);

            return;
        }

        Arr::set($this->config, $key, $value);
    }

    /**
     * 获取配置
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return array
     */
    public function getConfig(string $key = null, $default = null)
    {
        if (func_num_args() == 0) {
            return $this->config;
        } else {
            return Arr::get($this->config, $key, $default);
        }
    }
}
