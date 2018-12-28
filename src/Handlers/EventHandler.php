<?php

namespace Largezhou\WechatMenu\Handlers;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use Largezhou\WechatMenu\WechatApp;

class EventHandler implements EventHandlerInterface
{
    protected $logger;

    public function __construct()
    {
        $this->logger = WechatApp::getApp()->logger;
    }

    public function handle($payload = null)
    {
        $this->logger->info('[payload] '.json_encode($payload, JSON_UNESCAPED_UNICODE));

        $event = $payload['Event'] ?? '';
        switch ($event) {
            case 'CLICK':
                return $this->handleClickEvent($payload);
            default:
                return '';
        }
    }

    /**
     * 处理公众号菜单点击事件
     *
     * @param $payload
     *
     * @return mixed|string
     */
    protected function handleClickEvent($payload)
    {
        $eventKey = $payload['EventKey'] ?? '';

        // TODO 从数据读取该配置
        $handlers = [
            'TEST' => 'App\Services\WechatService@test',
        ];

        $handler = $handlers[$eventKey] ?? null;

        if (!$handler) {
            return '';
        }

        list($class, $method) = explode('@', $handler);

        return call_user_func([new $class(), $method], $payload);
    }
}
