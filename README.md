<h1 align="center"> wechat-menu </h1>

<p align="center">可视化的微信菜单管理组件</p>

## DEMO

[http://wechat-menu.lzis.me](http://wechat-menu.lzis.me)

## 更新

- 菜单可选择跳转小程序。 (2019-6-13)
- 自动回复消息，现已支持回复纯文本以及图文、图片、音频和视频永久素材消息。永久素材需要在公众号后台添加。 (2019-1-16)

## 安装

```shell
$ composer require largezhou/wechat-menu -vvv
```

## 配置

参考 [`src/config.php`](src/config.php) 文件

## 使用

### 首先的首先

在该扩展使用前，需要先进行配置 `Manager::getInstance()->setConfig($anyConfigs)`。

然后把 [`public`](public) 下的静态资源，复制一份到你项目的，一般也为 `public` 文件夹下的 `vendor/wechat-menu` 目录下。

### 首先

你需要定义一个 `get` 路由，来返回公众号管理页面

使用 `Content::renderWechatMenu()` 获取页面内容，你可以嵌在你的其他页面模板中。

### 其次

在你的控制其中使用 `Largezhou\WechatMenu\Controller` 这个 `trait` ，该 `trait` 提供一个方法 `resources`。是处理页面中所有 `ajax` 请求的方法。该方法会通过请求数据中的 `type` 字段的值和请求方法来区分获取和保存不同的数据。

`anyRequestMethod@anyMethodName` 用来启动微信服务。使用 `Manager::getInstance()->serve()->send()` 来启动服务。具体参考 [EasyWechat 文档](https://www.easywechat.com/docs/master/official-account/server)

### 最后

该扩展使用一个 `json` 文件来保存设置数据，如果你需要用数据库来保存，可以重写 `Data` 中的部分方法来实现。

## 依赖

### 前端

- Vue.js - Vue
- Vue.Draggable - 拖拽排序
- Vuelidate - 表单验证

### 后端

- EasyWeChat - 可能是最好用的微信非官方 SDK

## 灵感 & 参考

`FastAdmin` 中的一个插件 [微信管理](https://www.fastadmin.net/store/wechat.html)

## License

MIT
