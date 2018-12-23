const mix = require('laravel-mix')
const webpack = require('webpack')

// 去掉生成 mix-manifest.json 文件
Mix.manifest.refresh = () => {}

mix
    .options({
        uglify: {
            uglifyOptions: {
                compress: {
                    drop_console: true,
                },
            },
        },
    })
    .setPublicPath('public')
    .js('resources/js/app.js', 'public')
    .sass('resources/sass/app.scss', 'public')
    .copy('public', '../test_wechat_menu/public/vendor/wechat-menu')
    .webpackConfig({
        resolve: {
            symlinks: false,
            alias: {
                '@': path.resolve(__dirname, 'resources/js/'),
            },
        },
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
        ],
    })
