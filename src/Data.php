<?php

namespace Largezhou\WechatMenu;

use Largezhou\WechatMenu\Exceptions\WechatMenuException;

class Data
{
    /**
     * 允许的请求类型.
     */
    const REQUEST_TYPES = [
        // 公众号菜单
        'menus',
        // 菜单事件配置
        'menu_events',
        // 其他事件配置
        'other_events',
        // 素材列表
        'materials',
        // 素材详情
        'media',
    ];

    /**
     * 永久素材类型.
     */
    const MATERIAL_TYPES = [
        'news',
        'image',
        'video',
        'voice',
    ];

    /**
     * 素材每页数.
     */
    const MEDIA_PER_PAGE = 3;

    /**
     * 返回成功.
     *
     * @param string $msg  消息
     * @param string $data 附带数据
     *
     * @return string
     */
    public static function success($msg = '', $data = null): string
    {
        return json_encode(
            [
                'status' => true,
                'msg' => $msg,
                'data' => $data,
            ]
        );
    }

    /**
     * 返回错误.
     *
     * @param string $msg  消息
     * @param string $type 错误类型: default 默认，wechat 微信接口错误
     *
     * @return string
     */
    public static function error($msg = '', $type = 'default'): string
    {
        return json_encode(
            [
                'status' => false,
                'msg' => $msg,
                'type' => $type,
            ]
        );
    }

    /**
     * 获取存储的数据.
     *
     * @param string|null $type
     *
     * @return array|mixed|null
     */
    public static function getData(string $type = null)
    {
        $data = safe_json_decode(@file_get_contents(Manager::getInstance()->getConfig('data_path')), []);

        if (!$type) {
            return $data;
        } else {
            return $data[$type] ?? [];
        }
    }

    /**
     * 存储数据.
     *
     * @param mixed $allData 需要保存的数据
     */
    public static function saveData($allData)
    {
        file_put_contents(Manager::getInstance()->getConfig('data_path'), $allData);
    }

    /**
     * 返回从微信服务器获取的公众号菜单.
     *
     * @return string
     *
     * @throws Exceptions\WechatMenuException
     */
    public static function getMenus(): string
    {
        return static::success('', Manager::getInstance()->getWechat()->menu->list());
    }

    /**
     * 创建公众号菜单.
     *
     * @param array $menus
     *
     * @return string
     *
     * @throws Exceptions\WechatMenuException
     */
    public static function postMenus(array $menus): string
    {
        if (empty($menus)) {
            return static::error('至少要有一个菜单才能保存');
        }

        $res = Manager::getInstance()->getWechat()->menu->create($menus);

        if ($res['errcode'] == 0) {
            return static::success('菜单保存成功');
        } else {
            return static::error("[{$res['errcode']}] {$res['errmsg']}", 'wechat');
        }
    }

    /**
     * 获取指定类型的数据.
     *
     * @param array $data 请求中的数据
     *
     * @return string json_encode 后的数据
     *
     * @throws WechatMenuException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public static function getResources(array $data)
    {
        $type = static::checkAndGetType($data);

        switch ($type) {
            case 'menus':
                return static::getMenus();
            case 'materials':
                return static::getMaterials($data);
            case 'media':
                return static::getMedia($data);
            default:
                return static::getSettings($type);
        }
    }

    /**
     * 存储指定类型的数据.
     *
     * @param array $data
     *
     * @return string
     *
     * @throws WechatMenuException
     */
    public static function postResources(array $data): string
    {
        $type = static::checkAndGetType($data);

        if ($type == 'menus') {
            return static::postMenus($data['data'] ?? null);
        } else {
            return static::postSettings($type, $data['data'] ?? null);
        }
    }

    /**
     * 获取指定键的数据.
     *
     * @param string $type
     *
     * @return string
     */
    public static function getSettings($type): string
    {
        return static::success('', static::getData($type));
    }

    /**
     * 保存来自请求的数据.
     *
     * @param string $type
     * @param mixed  $data
     *
     * @return string
     */
    public static function postSettings(string $type, $data): string
    {
        $allData = static::getData();
        $allData[$type] = $data;
        $allData = json_encode($allData, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);

        static::saveData($allData);

        return static::success('保存设置成功');
    }

    /**
     * 检测请求中的 type 值，并返回.
     *
     * @param array $data 请求中的数据
     *
     * @return string
     *
     * @throws WechatMenuException
     */
    protected static function checkAndGetType(array $data): string
    {
        $type = trim($data['type'] ?? '');

        if (!$type) {
            throw new WechatMenuException('请求中 type 不能为空');
        }

        if (!in_array($type, static::REQUEST_TYPES)) {
            throw new WechatMenuException('请求中 type 参数不正确');
        }

        return $type;
    }

    /**
     * 获取素材列表.
     *
     * @param array $data
     *
     * @return string
     *
     * @throws WechatMenuException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected static function getMaterials(array $data): string
    {
        $materialType = $data['material_type'] ?? '';

        if (!in_array($materialType, static::MATERIAL_TYPES)) {
            throw new WechatMenuException('请求中素材类型 material_type 参数不正确');
        }

        // switch ($materialType) {
        //     case 'news':
        //         $res = '{"status":true,"msg":"","data":{"item":[{"media_id":"eJVZz7nQs2HT-qsr4tPaYTApI9hu7Bm5X3cHJZZYGsY","content":{"news_item":[{"title":"\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc71","author":"\u5468\u971c\u9716","digest":"\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc71","content":"<p>\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc71<\/p>","content_source_url":"","thumb_media_id":"eJVZz7nQs2HT-qsr4tPaYRVjWutSY7tdg4QVc04GMPY","show_cover_pic":0,"url":"http:\/\/mp.weixin.qq.com\/s?__biz=MzI0Mjg1MzAxMQ==&mid=100000009&idx=1&sn=dc198c0b9f9a9d8e6b658c2c01edd985&chksm=6974b4f55e033de30ea8f026428cb15a31d1f733ff5f91738e9b133c19c1b332ab1abb6f2029#rd","thumb_url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQiboYOcLZ97lCY8opMloibMDicm1Pt8ibs1fcicHDda6K7MLVYiaQPPy4CF9BLwDlw6EBcAoO2NQEibWWK0kw\/0?wx_fmt=jpeg","need_open_comment":1,"only_fans_can_comment":0},{"title":"\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc72","author":"\u5468\u971c\u9716","digest":"\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc72","content":"<p>\u6d4b\u8bd5\u56fe\u6587\u662f\u4e0d\u662f\u53ef\u4ee5\u591a\u7bc72<\/p>","content_source_url":"","thumb_media_id":"eJVZz7nQs2HT-qsr4tPaYRbOfM8m8Ff9aAgJkWQvkI4","show_cover_pic":0,"url":"http:\/\/mp.weixin.qq.com\/s?__biz=MzI0Mjg1MzAxMQ==&mid=100000009&idx=2&sn=e432f9c560e87a54d7bc68e3a42d79c8&chksm=6974b4f55e033de3de2499d0a1489936d90f936cbe0865a76dfcdbef43a58a160ccca8d2ec8c#rd","thumb_url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQiboYOcLZ97lCY8opMloibMDicmRuXoC6tNIKI0p1KV7XrtBT4iaq2iaEeciarTjnHZSeAuCuUeKJ0QMp7jQ\/0?wx_fmt=jpeg","need_open_comment":1,"only_fans_can_comment":0}],"create_time":1547346987,"update_time":1547347022},"update_time":1547347022},{"media_id":"eJVZz7nQs2HT-qsr4tPaYSmw8CIBUjVPvnTPftbhpGM","content":{"news_item":[{"title":"\u8bd5\u4e0b","author":"\u5934\u4e0a\u6709\u7070\u673a","digest":"\u6d4b\u8bd5\u662f\u4e0d\u662f\u6309\u6700\u540e\u66f4\u65b0\u65f6\u95f4\u6392\u5e8f","content":"<p>&nbsp;&nbsp;&nbsp;&nbsp;\u6d4b\u8bd5\u662f\u4e0d\u662f\u6309\u6700\u540e\u66f4\u65b0\u65f6\u95f4\u6392\u5e8f<br  \/><\/p>","content_source_url":"","thumb_media_id":"eJVZz7nQs2HT-qsr4tPaYQ_B81f87LghkpFTjEYTexA","show_cover_pic":0,"url":"http:\/\/mp.weixin.qq.com\/s?__biz=MzI0Mjg1MzAxMQ==&mid=100000006&idx=1&sn=aa38425998020951cdc98c841e029966&chksm=6974b4fa5e033decc03c61e705b0cb32575f7737a655b48b58df33419dbfdfae711b6665427b#rd","thumb_url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQibp9pCvlpBBvP2mRxARXIuQYIXwV7kichqlQMbxA4gJwhN9ED4UmO6p9ATx50IV3pYia8ryQOHEgS4Ig\/0?wx_fmt=jpeg","need_open_comment":1,"only_fans_can_comment":0}],"create_time":1541055497,"update_time":1547350853},"update_time":1547350853}],"total_count":2,"item_count":2,"per_page":3}}';
        //         break;
        //     case 'image':
        //         $res = '{"status":true,"msg":"","data":{"item":[{"media_id":"eJVZz7nQs2HT-qsr4tPaYRbOfM8m8Ff9aAgJkWQvkI4","name":"CropImage","update_time":1547347013,"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQiboYOcLZ97lCY8opMloibMDicmRuXoC6tNIKI0p1KV7XrtBT4iaq2iaEeciarTjnHZSeAuCuUeKJ0QMp7jQ\/0?wx_fmt=jpeg"},{"media_id":"eJVZz7nQs2HT-qsr4tPaYRVjWutSY7tdg4QVc04GMPY","name":"CropImage","update_time":1547346957,"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQiboYOcLZ97lCY8opMloibMDicm1Pt8ibs1fcicHDda6K7MLVYiaQPPy4CF9BLwDlw6EBcAoO2NQEibWWK0kw\/0?wx_fmt=jpeg"},{"media_id":"eJVZz7nQs2HT-qsr4tPaYbfK_eHady5gv6FlW3OD4og","name":"CropImage","update_time":1547346957,"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/liaicg5kSaQiboYOcLZ97lCY8opMloibMDicmJLKxUga4ekG0rSkibVWz1e5og9FPxYTYxiba9otLQhUiaPBqSjtJJXiadA\/0?wx_fmt=jpeg"}],"total_count":1,"item_count":3,"per_page":3}}';
        //         break;
        //     case 'voice':
        //     case 'video':
        //         $res = '{"status":true,"msg":"","data":{"item":[{"media_id":"eJVZz7nQs2HT-qsr4tPaYZ5lZvSWXiBiQq7fcNBNkOA","name":"Sweet Dreams (Are Made of This).mp3","update_time":1547511262}],"total_count":1,"item_count":1,"per_page":3}}';
        //         break;
        // }
        //
        // $res = json_decode($res, true);
        // $res['data']['total_count'] = 100;
        // shuffle($res['data']['item']);
        //
        // return json_encode($res);

        $res = Manager::getInstance()
            ->getWechat()
            ->material
            ->list(
                $materialType,
                ($data['page'] - 1) * static::MEDIA_PER_PAGE,
                static::MEDIA_PER_PAGE
            );

        $errCode = $res['errcode'] ?? null;
        if ($errCode) {
            return static::error("[{$res['errcode']}] {$res['errmsg']}", 'wechat');
        }

        $res['per_page'] = static::MEDIA_PER_PAGE;

        return static::success('', $res);
    }

    /**
     * 获取素材详情
     *
     * @param array $data
     *
     * @return \EasyWeChat\Kernel\Http\StreamResponse|string
     *
     * @throws WechatMenuException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected static function getMedia(array $data)
    {
        $mediaId = $data['media_id'] ?? '';

        if (!$mediaId) {
            return static::error('必须有媒体 ID 参数：media_id');
        }

        $res = Manager::getInstance()->getWechat()->material->get($mediaId);

        // 其他则直接返回文件内容，前端打开可直接下载
        if ($res instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            return $res;
        }

        // 视频和图文会返回一个数组，里面有相应的信息
        $errCode = $res['errcode'] ?? null;
        if ($errCode) {
            return static::error("[{$res['errcode']}] {$res['errmsg']}", 'wechat');
        }

        return static::success('', $res);
    }
}
