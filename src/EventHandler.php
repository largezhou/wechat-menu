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

    // 订阅和取消订阅的 MsgType 为 event，单独处理一下
    // 其他则直接用 MsgType 判断
    const SUBSCRIBE_EVENTS = [
        'subscribe',
        'unsubscribe',
    ];

    protected $logger;
    protected $manager;

    public function __construct()
    {
        $this->manager = Manager::getInstance();
        $this->logger = $this->manager->getWechat()->logger;
    }

    public function handle($payload = null): string
    {
        $this->logger->info("[payload]\n".json_encode($payload, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT));

        $msgType = $payload['MsgType'];
        $eventType = $payload['Event'] ?? null;
        $eventKey = $payload['EventKey'] ?? null;

        if ($msgType == 'event' && in_array($eventType, static::MENU_EVENTS)) {
            return $this->handleMenuEvent($payload, $eventKey);
        } else {
            return $this->handleOtherEvent($payload, $msgType, $eventType);
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
    protected function handleMenuEvent(array $payload, string $eventKey): string
    {
        if (!$eventKey) {
            $this->logger->error('无法获取到 EventKey');

            return '';
        }

        return $this->resolveHandler($payload, 'menu_events', $eventKey);
    }

    /**
     * 处理其他事件
     *
     * @param array  $payload
     * @param string $msgType
     * @param string $eventType
     *
     * @return string
     */
    protected function handleOtherEvent(array $payload, string $msgType, string $eventType = null): string
    {
        // 订阅和取消订阅的 MsgType 为 event，单独处理一下
        if ($msgType == 'event' && in_array($eventType, static::SUBSCRIBE_EVENTS)) {
            $msgType = $eventType;
        }

        return $this->resolveHandler($payload, 'other_events', $msgType);
    }

    /**
     * 调用回调
     *
     * @param array  $payload
     * @param string $dataType 回调设置数据中的 key（分组）
     * @param string $key 事件标识
     *
     * @return string
     */
    protected function resolveHandler(array $payload, string $dataType, string $key): string
    {
        $eventHandler = null;
        foreach (Data::getData($dataType) as $e) {
            if ($e['key'] == $key) {
                $eventHandler = $e;
                break;
            }
        }

        if (!$eventHandler) {
            $this->logger->error("没有与 [{$key}] 对应的处理方法");

            return '';
        }

        $type = $eventHandler['type'];
        $content = $eventHandler['content'];

        if (!$content) {
            $this->logger->error('处理内容为空');

            return '';
        }

        // 自动回复消息
        if ($type == 'msg') {
            return $content;
        }

        // 回调处理
        try {
            list($class, $method) = explode('@', $content);
        } catch (\Exception $e) {
            $this->logger->error("事件处理回调格式错误: [{$content}]");

            return '';
        }

        try {
            $res = call_user_func([new $class(), $method], $payload);
        } catch (\Throwable $e) {
            $res = Manager::getInstance()->getConfig('handler_error_msg');
            $this->logger->error(
                "事件处理回调出错: "
                .PHP_EOL
                .$e->getMessage()
                .PHP_EOL
                .$e->getTraceAsString()
            );
        }

        return $res;
    }
}
