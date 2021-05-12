const path = require('path');
const webpack = require('webpack');
const mix = require('laravel-mix');
const WebpackShellPluginNext = require('webpack-shell-plugin-next');
const WebpackWatchPlugin = require('webpack-watch-files-plugin').default;
const activeThemeFiles = [];

const replacementsFiles = require('./resources/themes/' + process.env.APP_THEME + '/config.js');
const migrationCmd = require('./resources/themes/' + process.env.APP_THEME + '/config-migration.js');
const rollbackCmd = require('./resources/themes/' + process.env.APP_THEME + '/config-rollback.js');
for (let i = 0; i < migrationCmd.array.length; i++) {
  activeThemeFiles.push(
    migrationCmd.array[i]
  );
}
for (let i = 0; i < replacementsFiles.array.length; i++) {
  activeThemeFiles.push(
    'cp resources/themes/' + process.env.APP_THEME + '/files/' + replacementsFiles.array[i]
  );
}

mix
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/admin.js', 'public/js')
  .sass('resources/sass/vendor.scss', 'public/css')
  .sass('resources/sass/app.scss', 'public/css', {
       data: '$appTheme:\'' + process.env.APP_THEME + '\';'
  })
  .copyDirectory('resources/themes/' + process.env.APP_THEME + '/images', 'public/theme')
  .sourceMaps()
  // .disableNotifications()

if (mix.inProduction() || true) {
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
  devtool: 'hidden-source-map',
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
      onBeforeBuild: {
        scripts: migrationCmd.array,
        blocking: true,
        parallel: false
      },
      onBuildStart:{
        scripts: activeThemeFiles,
        blocking: true,
        parallel: false
      },
      onBuildEnd: {
        scripts: rollbackCmd.array,
        blocking: true,
        parallel: false
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
