const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

if(!mix.inProduction()){
  mix.webpackConfig({ devtool: "inline-source-map" });
}
mix.copyDirectory('./node_modules/@fortawesome/fontawesome-free/webfonts/', 'public/fonts');
mix.copyDirectory('./node_modules/@fortawesome/fontawesome-free/webfonts/', 'public/webfonts');

mix.options({
  processCssUrls: false,
});

mix.js('resources/js/app.js', 'public/js')
  .vue()
  .postCss('resources/css/app.css', 'public/css', [
    require("tailwindcss"),
  ])
  .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps(false)

