<?php

return [
    /**
     * axios 请求的路由前缀，例如：/wechat-menu/menus
     */
    'routePrefix' => 'wechat-menu',

    /**
     * 可选的事件 key 对应的处理方式
     */
    'eventsCallbacks' => [
        [
            'key' => 'TEST_KEY_1',
            /**
             * 自动回复 content 键中的消息
             */
            'type' => 'msg',
            'content' => '自动回复消息',
        ],
        [
            'key' => 'TEST_KEY_2',
            /**
             * 自动调用 (new Path\To\HandlerClass())->handle($payload)
             */
            'type' => 'callback',
            'content' => 'Path\To\HandlerClass@handle',
        ],
    ],

    /**
     * 存储微信菜单和事件设置的文件
     * 可以自行用其他方式来存储和读取
     */
    'data_path' => '/path/to/wechat_menu.json',

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
                    'path' => storage_path('logs/wechat.log'),
                    'level' => 'debug',
                ],
                // 生产环境
                'prod' => [
                    'driver' => 'daily',
                    'path' => storage_path('logs/wechat.log'),
                    'level' => 'info',
                ],
            ],
        ],
    ],
];
