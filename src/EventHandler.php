<?php

namespace Largezhou\WechatMenu;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

class EventHandler implements EventHandlerInterface
{
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

        $eventKey = $payload['EventKey'] ?? null;

        if (!$eventKey) {
            $this->logger->error('无法获取到 EventKey');

            return;
        }

        $eventHandler = null;
        foreach (Data::getData('events') as $e) {
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

        switch ($type) {
            case 'msg':
                return $content;
                break;
            case 'callback':
                list($class, $method) = explode('@', $content);

                try {
                    $res = call_user_func([new $class($payload), $method], $payload);
                } catch (\Exception $e) {
                    $res = Manager::getInstance()->getConfig('handler_error_msg');
                    $this->logger->error("事件处理回调出错：\n".$e->getMessage().PHP_EOL.$e->getTraceAsString());
                }

                return $res;
                break;
        }
    }
}
