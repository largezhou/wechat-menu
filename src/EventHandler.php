<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

class EventHandler implements EventHandlerInterface
{
    const MENU_EVENTS = [
        'CLICK', // 点击
        'VIEW', // 链接
        'scancode_push', // 扫码推
        'scancode_waitmsg', // 扫码推带提示信息
        'pic_sysphoto', // 系统拍照
        'pic_photo_or_album', // 拍照或相册
        'pic_weixin', // 相册
        'location_select', // 位置选择
    ];

    protected $logger;
    protected $manager;

    public function __construct()
    {
        $this->manager = Manager::getInstance();
        $this->logger = $this->manager->getWechat()->logger;
    }

    public function handle($payload = null)
    {
        $this->logger->info("[payload]\n".json_encode($payload, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT));

        $msgType = $payload['MsgType'];
        $eventType = $payload['Event'] ?? null;
        $eventKey = $payload['EventKey'] ?? null;

        if ($msgType == 'event' && in_array($eventType, static::MENU_EVENTS)) {
            return $this->handleMenuEvent($payload, $eventKey);
        }
    }

    /**
     * 处理菜单点击事件
     *
     * @param array  $payload
     * @param string $eventKey
     *
     * @return string
     */
    protected function handleMenuEvent($payload, $eventKey)
    {
        if (!$eventKey) {
            $this->logger->error('无法获取到 EventKey');

            return;
        }

        $eventHandler = null;
        foreach (Data::getData('menu_events') as $e) {
            if ($e['key'] == $eventKey) {
                $eventHandler = $e;
                break;
            }
        }

        if (!$eventHandler) {
            $this->logger->error("没有与 {$eventKey} 对应的处理方法");

            return;
        }

        $type = $eventHandler['type'];
        $content = $eventHandler['content'];

        // 自动回复消息
        if ($type == 'msg') {
            return $content;
        }

        // 回调处理
        list($class, $method) = explode('@', $content);
        try {
            $res = call_user_func([new $class($payload), $method], $payload);
        } catch (\Exception $e) {
            $res = Manager::getInstance()->getConfig('handler_error_msg');
            $this->logger->error("事件处理回调出错：\n".$e->getMessage().PHP_EOL.$e->getTraceAsString());
        }

        return $res;
    }
}
