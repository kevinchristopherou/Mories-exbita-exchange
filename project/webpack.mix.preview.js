const path = require('path');
const webpack = require('webpack');
const mix = require('laravel-mix');
const WebpackShellPluginNext = require('webpack-shell-plugin-next');
const WebpackWatchPlugin = require('webpack-watch-files-plugin').default;
const activeThemeFiles = [
  // components override
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Navbar.htm') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Navbar.vue') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/AdminNavbar.htm') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/AdminNavbar.vue') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Bottombar.htm') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Bottombar.vue') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Topbar.htm') + ' resources/js/components/',
  'cp ' + path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/files/components/Topbar.vue') + ' resources/js/components/',
];

const replacementsFiles = require('./resources/themes/' + process.env.APP_THEME + '/config.js');
for (let i = 0; i < replacementsFiles.array.length; i++) {
  activeThemeFiles.push(
    'cp resources/themes/' + process.env.APP_THEME + '/files/' + replacementsFiles.array[i]
  );
}
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix
  .js('resources/js/app.js', 'public/site-preview/js')
  .js('resources/js/admin.js', 'public/site-preview/js')
  .sass('resources/sass/vendor.scss', 'public/site-preview/css')
  .sass('resources/sass/app.scss', 'public/site-preview/css', {
       data: '$appTheme:\'' + process.env.APP_THEME + '\';'
    })
  .setPublicPath('public/site-preview')
  .sourceMaps()
  .disableNotifications()

if (mix.inProduction() || true) {
  mix.version();

  mix.extract([
    //vue packages
    'vue',
    'vue-i18n',
    'vue-meta',
    'vue-router',
    'vuex',
    'vuex-router-sync',
    'vform',
    //other packages
    'axios',
    'jquery',
    'jquery-easing',
    'popper.js',
    'js-cookie',
    'sweetalert2',
    'bootstrap',
    'bootstrap-table',
    'bootstrap-vue',
    'moment-timezone'
  ])
}

mix.webpackConfig({
  plugins: [
    // new BundleAnalyzerPlugin(),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      Popper: ['popper.js', 'default'],
      moment: 'moment'
    }),
    new WebpackShellPluginNext({
      onBuildStart:{
        scripts: activeThemeFiles,
        blocking: true,
        parallel: false
      }, 
      onWatchRun: {
        scripts: activeThemeFiles,
        blocking: true,
        parallel: false
      },
      onBuildEnd:{
        scripts: ['echo "Webpack End"'],
        blocking: false,
        parallel: true
      }
    }),
    new WebpackWatchPlugin({
      files: [
        path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/**/*.htm'),
        path.join(__dirname, '/resources/themes/' + process.env.APP_THEME + '/**/*.vue'),
      ]
    })
  ],
  resolve: {
    alias: {
      '~': path.join(__dirname, './resources/js')
    }
  }
});
