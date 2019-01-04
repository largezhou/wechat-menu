const mix = require('laravel-mix')
const webpack = require('webpack')
const path = require('path')

// 修改生成 mix-manifest.json 文件的位置
Mix.manifest.refresh = function() {
    File.find(path.join('resources', this.name))
        .makeDirectories()
        .write(this.manifest)
}

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
    .version()
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
