<?php

return [
    /**
     * axios 请求的路由前缀，例如：/wechat-menu/menus
     */
    'routePrefix' => 'wechat-menu',

    /**
     * 存储微信菜单和事件设置的文件
     * 可以自行用其他方式来存储和读取
     */
    'data_path' => '/path/to/wechat_menu.json',

    /**
     * 事件回调出错的消息
     */
    'handler_error_msg' => '服务器开小差了',

    /**
     * easy wechat 扩展的配置
     */
    'easyWechat' => [
        'app_id' => 'app_id',
        'secret' => 'secret',
        'token' => 'token',

        'log' => [
            'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
            'channels' => [
                // 测试环境
                'dev' => [
                    'driver' => 'single',
                    // 'path' => 'logs/wechat.log',
                    'level' => 'debug',
                ],
                // 生产环境
                'prod' => [
                    'driver' => 'daily',
                    // 'path' => 'logs/wechat.log',
                    'level' => 'info',
                ],
            ],
        ],
    ],
];
