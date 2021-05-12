@if(isset($ga_code))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga_code }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $ga_code }}');
    </script>
@endif

{{-- Global configuration object --}}
@php
    $config = [
        'site' => SiteSettingsHelper::getPublicSettings(),
        'appName' => config('app.name'),
        'smsTimeout' => config('sms.timeout'),
        'locale' => $locale = app()->getLocale(),
        'locales' => \App\Helpers\TranslationsHelper::getLocales(),
        'translations' => \App\Helpers\TranslationsHelper::getTranslationsString($locale,$admin),
        'recaptchaSiteKey' => config('captcha.sitekey'),
        'socketUrl' => config('app.socketurl'),
        'appTheme' => config('app.app_theme'),
        'ad' => $admin,
        'baseUrl' => config('app.api_url'),
        'appUrl' => config('app.url'),
        'tradeCostFactor' => ExchangeHelper::getBuyCostFactor(),
        'tradeCommissionFactor' => ExchangeHelper::getCommissionFactor(),
        'tradeTaxFactor' => ExchangeHelper::getTaxFactor(),
        'kycState' => config('settings.KYC_STATE'),
        'maxFileSize' => ini_get("upload_max_filesize"),
        'mainFiatCurrency' => ExchangeHelper::getMainFiatcurrency()
    ];
@endphp
<script>window.config = {!! json_encode($config); !!};</script>

{{-- Polyfill some features via polyfill.io --}}
@php
    $polyfills = [
        'Promise',
        'Object.assign',
        'Object.values',
        'Array.prototype.find',
        'Array.prototype.findIndex',
        'Array.prototype.includes',
        'String.prototype.includes',
        'String.prototype.startsWith',
        'String.prototype.endsWith',
    ];
@endphp
<script src="https://www.google.com/recaptcha/api.js?hl={{$locale}}" async defer></script>
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

{{-- Load the application scripts --}}
@if(isset($preview))
    <script src="{{ mix('js/manifest.js','site-preview') }}"></script>
    <script src="{{ mix('js/vendor.js','site-preview') }}"></script>
    <script src="{{ mix('js/app.js','site-preview') }}"></script>
@else
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>

    @if($admin)
        <script src="{{ mix('js/admin.js') }}"></script>
    @else
        <script src="{{ mix('js/app.js') }}"></script>
    @endif

@endif
