let mix = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    })
}

mix.sass('resources/assets/sass/app.scss', 'public/css').version();
mix.sass('resources/assets/sass/style.scss', 'public/css').version();

