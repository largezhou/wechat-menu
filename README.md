<h1 align="center"> wechat-menu </h1>

<p align="center">可视化的微信菜单管理组件</p>


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

### 首先，你需要定义三个 `get` 路由，来返回下面三个页面：

`Content::renderMenusEditor()` 返回菜单编辑页的网页内容，你可以把该内容，插入到你的模板中相应的位置。

`Content::renderMenuEventsSetting()` 返回自定义菜单事件配置页的网页内容。

`Content::renderOtherEventsSetting()` 返回其他事件，比如收到文字，用户关注等处理方法的配置的网页。

### 其次，你需要定义 5 个路由，来获取或者保存数据和启动微信服务：

`get@menus` 用来获取微信菜单配置。该控制器方法中可直接调用 `Data::getMenus()` 获取菜单。

`post@menus` 用来发布微信菜单配置。使用 `Data::createMenus($_POST['data'])` 发布菜单。

`get@settings` 用来获取自定义微信菜单事件处理配置和其他事件配置。使用 `Data::getSettings($_GET['key'])` 获取配置。

`post@settings` 用来保存事件处理配置。使用 `Data::saveSettings($_POST['key'], _POST['data'])` 保存配置。

`post@anyMethodName` 用来启动微信服务。使用 `Manager::getInstance()->serve()->send()` 来启动服务。具体参考 [EasyWechat 文档](https://www.easywechat.com/docs/master/official-account/server)

注意1：由于不是使用表单提交的数据，`$_POST` 中可能获取不到数据，可使用 `file_get_contents("php://input")` 或者你正在用的框架中的相应的获取 `post` 数据的方法。

注意2：上面的 5 个路由中，使用的方法，都可以自行定义，只要返回的数据格式保持一致即可。该组件默认把数据保存在配置中 `data_path` 设置的路径。你自行定义的话，可以保存到数据库，或者为其他形式。

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
