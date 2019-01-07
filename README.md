<h1 align="center"> wechat-menu </h1>

<p align="center">可视化的微信菜单管理组件</p>

## DEMO

[http://test_wechat_menu.l.com](http://test_wechat_menu.l.com)

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

你需要定义三个 `get` 路由，来返回下面三个页面：

`Content::renderMenusEditor()` 返回菜单编辑页的网页内容，你可以把该内容，插入到你的模板中相应的位置。

`Content::renderMenuEventsSetting()` 返回自定义菜单事件配置页的网页内容。

`Content::renderOtherEventsSetting()` 返回其他事件，比如收到文字，用户关注等处理方法的配置的网页。

### 其次

在你的控制其中使用 `Largezhou\WechatMenu\Controller` 这个 `trait` ，该 `trait` 提供一个方法 `resources`。是处理页面中所有 `ajax` 请求的方法。该方法会通过请求数据中的 `type` 字段的值和请求方法来区分获取和保存不同的数据。

`anyRequestMethod@anyMethodName` 用来启动微信服务。使用 `Manager::getInstance()->serve()->send()` 来启动服务。具体参考 [EasyWechat 文档](https://www.easywechat.com/docs/master/official-account/server)

### 最后

该扩展使用一个 `json` 文件来保存设置数据，如果你需要用数据库来保存，可以重写 `Data` 中的部分方法来实现。

## TODO

不是很清楚微信的业务，所以不知道还有啥，暂时想到的还有的如下：

- [ ] 个性化菜单

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
