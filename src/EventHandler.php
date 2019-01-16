<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Media;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

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

    /**
     * 自动回复类型.
     */
    const AUTO_REPLY_TYPES = [
        'text',
        'news',
        'image',
        'video',
        'voice',
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

        $msgType = $payload['MsgType'] ?? null;

        if (!$msgType) {
            return '';
        }

        $eventType = $payload['Event'] ?? null;
        $eventKey = $payload['EventKey'] ?? null;

        if ($msgType == 'event' && in_array($eventType, static::MENU_EVENTS)) {
            return $this->handleMenuEvent($payload, $eventKey);
        } else {
            return $this->handleOtherEvent($payload, $msgType, $eventType);
        }
    }

    /**
     * 处理菜单点击事件.
     *
     * @param array  $payload
     * @param string $eventKey
     *
     * @return mixed
     */
    protected function handleMenuEvent(array $payload, string $eventKey)
    {
        if (!$eventKey) {
            $this->logger->error('无法获取到 EventKey');

            return '';
        }

        return $this->resolveHandler($payload, 'menu_events', $eventKey);
    }

    /**
     * 处理其他事件.
     *
     * @param array  $payload
     * @param string $msgType
     * @param string $eventType
     *
     * @return mixed
     */
    protected function handleOtherEvent(array $payload, string $msgType, string $eventType = null)
    {
        // 订阅和取消订阅的 MsgType 为 event，单独处理一下
        if ($msgType == 'event' && in_array($eventType, static::SUBSCRIBE_EVENTS)) {
            $msgType = $eventType;
        }

        return $this->resolveHandler($payload, 'other_events', $msgType);
    }

    /**
     * 调用回调.
     *
     * @param array  $payload
     * @param string $dataType 回调设置数据中的 key（分组）
     * @param string $key      事件标识
     *
     * @return mixed
     */
    protected function resolveHandler(array $payload, string $dataType, string $key)
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
            $this->logger->info('处理内容为空');

            return '';
        }

        // 自动回复消息
        if ($type == 'msg') {
            try {
                return $this->resolveMsg($content);
            } catch (\Throwable $e) {
                return $this->errorResponse($e, '自动回复消息失败');
            }
        }

        // 回调处理
        try {
            list($class, $method) = explode('@', $content);
        } catch (\Exception $e) {
            $this->logger->error("事件处理回调格式错误: [{$content}]");

            return '';
        }

        try {
            return call_user_func([new $class(), $method], $payload);
        } catch (\Throwable $e) {
            return $this->errorResponse($e, '事件处理回调出错');
        }
    }

    /**
     * 处理自动回复消息.
     *
     * @param array|null $content
     *
     * @return Media|News|mixed|string|null
     */
    protected function resolveMsg(array $content = null)
    {
        if (!$content) {
            $this->logger->info('自动回复内容为空');

            return '';
        }

        $type = $content['type'] ?? null;
        $value = $content['value'] ?? null;

        if (!in_array($type, static::AUTO_REPLY_TYPES)) {
            $this->logger->info('自动回复类型不对');

            return '';
        }

        if (!$value) {
            $this->logger->info('自动回复内容为空');

            return '';
        }

        $mediaId = $value['media_id'] ?? '';
        $this->logger->info('回复消息的 media_id：'.$mediaId);

        switch ($type) {
            case 'text':
                return $value;
            case 'news':
                return $this->getNews($value);
            case 'image':
            case 'voice':
            case 'video':
                return new Media($mediaId, $type == 'video' ? 'mpvideo' : $type);
        }
    }

    /**
     * 组装图文.
     *
     * @param array $value
     *
     * @return News|string
     */
    protected function getNews(array $value)
    {
        $newsItems = $value['content']['news_item'] ?? null;

        if (!$newsItems) {
            $this->logger->info('自动回复内容为空');

            return '';
        }

        $resItems = [];
        foreach ($newsItems as $item) {
            $resItems[] = new NewsItem([
                'title' => $item['title'] ?? '',
                'description' => $item['digest'] ?? '',
                'image' => $item['thumb_url'] ?? '',
                'url' => $item['url'] ?? '',
            ]);
        }

        return new News($resItems);
    }

    /**
     * log 错误，并返回设置好的错误消息
     *
     * @param \Throwable $e
     * @param string     $msg
     *
     * @return string
     */
    protected function errorResponse(\Throwable $e, $msg): string
    {
        $this->logger->error(
            $msg
            .PHP_EOL
            .$e->getMessage()
            .PHP_EOL
            .$e->getTraceAsString()
        );

        return Manager::getInstance()->getConfig('handler_error_msg');
    }
}
