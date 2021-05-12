<!DOCTYPE HTML>
<html>
<head>
    <title>Exbita {{$market}} Charts</title>
    <!-- Fix for iOS Safari zooming bug -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <script type="text/javascript" src="/trading-view/charting_library/charting_library.standalone.js"></script>
    <script type="text/javascript" src="/trading-view/charting_library/charting_library_custom.js"></script>
    <script type="text/javascript" src="/trading-view/datafeeds/udf/dist/polyfills.js"></script>
    <script type="text/javascript" src="/trading-view/datafeeds/udf/dist/bundle.js"></script>
    {{-- <script type="text/javascript" src="/trading-view/standalone.js"></script> --}}

    <script type="text/javascript">
        "use strict";
      document.addEventListener('DOMContentLoaded', function(){
          document.body.style.height = '400px';
      });
      let displayMode = localStorage.getItem('display-mode');
        let displayTheme = localStorage.getItem('active-theme');
        let display = 'Dark';
        if(displayMode && (displayMode != 'dark') && (displayTheme == 'dark-light')) {
            display = 'Light';
        }
      document.addEventListener('DOMContentLoaded', function () {
          new TradingView.widget(
              {
                  "autosize": true,
                  "user_id": 'kriptoist',
                  "client_id": 'tradingview.com',
                  "symbol": "{{$market}}",
                  "interval": "60",
                  "timezone": getTzByTimezone(),
                  "theme": display,
                  "style": "0",
                  "locale": getParameterByName('locale') || "en",
                  "allow_symbol_change": false,
                  "container_id": "tv_chart_container",
                  "disabled_features": ["use_localstorage_for_settings", "volume_force_overlay", "compare_symbol", "header_compare", "header_symbol_search", "header_saveload"],
                  "enabled_features": ["study_templates", "keep_left_toolbar_visible_on_small_screens", "hide_left_toolbar_by_default"],
                  "charts_storage_url": "{{$tradingviewUrl}}",
                  "charts_storage_api_version": "1.1",
                  "fullscreen": true,
                  "datafeed": new Datafeeds.UDFCompatibleDatafeed("{{url('api/v1/tradingviewudf')}}"),
                  "library_path": "/trading-view/charting_library/",
                  "drawings_access": {type: 'black', tools: [{name: "Regression Trend"}]},
              }
          );
      });
    </script>
</head>
<body class="body-no-margin body-height-400">
<div id="tv_chart_container"></div>
</body>
</html>
