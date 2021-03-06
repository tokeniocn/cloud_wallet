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

mix.setPublicPath('public')
  .options({
    fileLoaderDirs: {
      images: 'build/images',
      fonts: 'build/fonts'
    },
  })

  /* ============ admin ============ */
  .sass('resources/sass/admin/app.scss', 'build/css/admin.css')
  .js([
    'resources/js/admin/app.js'
  ], 'build/js/admin.js')

  /* ============ frontend ============ */
  // .js('resources/js/app.js', 'public/js')
  // .sass('resources/sass/app.scss', 'public/css')

  .extract([
    'vue',
    'axios',
    'lodash',
    'moment',
  ])
  .version()
  .sourceMaps();

if (mix.inProduction()) {
  mix.options({
      // Optimize JS minification process
      terser: {
        cache: true,
        parallel: true,
        sourceMap: true
      }
    });
} else {
  // Uses inline source-maps on development
  mix.webpackConfig({
    devtool: 'inline-source-map'
  });
}
