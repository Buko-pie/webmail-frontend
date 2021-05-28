const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

if(!mix.inProduction()){
  mix.webpackConfig({ devtool: "inline-source-map" });
}

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/tablecell_override.scss', 'public/css')
    .sass('resources/sass/syncfusion_material.scss', 'public/css')
    .sourceMaps(false)
;
