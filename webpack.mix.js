let mix = require('laravel-mix');

mix
    .disableNotifications()
    .options({processCssUrls: false})
    .setPublicPath('public')
    .js('assets/admin/js/app.js', 'public/admin')
    .js('assets/site/js/app.js', 'public/site')
    .stylus('assets/admin/css/app.styl', 'public/admin')
    .stylus('assets/site/css/app.styl', 'public/site')
    .version()
;