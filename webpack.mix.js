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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/admin.js', 'public/js');

mix.copyDirectory('resources/images/icons/tiptap', 'public/icons');
mix.copyDirectory('resources/images/icons/flags', 'public/icons');
mix.copyDirectory('resources/images/favicons/', 'public/icons');

if (mix.inProduction()) {
  mix.version();
}

// mix.browserSync(process.env.APP_URL);
