<?php

namespace Largezhou\WechatMenu;

use Largezhou\WechatMenu\Exceptions\WechatMenuException;

trait Controller
{
    /**
     * 前端 ajax 请求的统一处理方法.
     *
     * @return string
     */
    public function resources()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD'] ?? '');

        try {
            if ($method == 'get') {
                return Data::getResources($_GET);
            } elseif ($method == 'post') {
                $data = safe_json_decode(file_get_contents('php://input'), []);

                return Data::postResources($data);
            } else {
                return Data::error('请求方法错误');
            }
        } catch (WechatMenuException $e) {
            return Data::error($e->getMessage());
        }
    }
}
