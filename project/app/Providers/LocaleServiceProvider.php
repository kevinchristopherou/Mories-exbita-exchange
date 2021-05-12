<?php

namespace App\Providers;

use App\Models\Locale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $locale = Cookie::get('lang_locale', $this->getBrowserLocale());
        if ($locale) {
            App::setLocale($locale);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Detects user language HTTP_ACCEPT_LANGUAGE header sent by browser.
     *
     * @return string
     */
    private function getBrowserLocale()
    {
        $websiteLanguages = Locale::orderBy('is_active', 'desc')->pluck('locale')->toArray();

        if(config('settings.DISABLE_BROWSER_LANGUAGE_DETECTION')) {
            return isset($websiteLanguages[0]) ? $websiteLanguages[0] : 'en';
        }

        if (! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return false;
        }

        preg_match_all(
            '/([a-z]{1,8})'.       // M1 - First part of language e.g en
            '(-[a-z]{1,8})*\s*'.   // M2 -other parts of language e.g -us
            // Optional quality factor M3 ;q=, M4 - Quality Factor
            '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i',
            $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            $langParse);

        $langs = $langParse[1]; // M1 - First part of language
        $quals = $langParse[4]; // M4 - Quality Factor

        $numLanguages = count($langs);
        $langArr = [];

        for ($num = 0; $num < $numLanguages; $num++) {
            $newLang = strtoupper($langs[$num]);
            $newQual = isset($quals[$num]) ?
                (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

            // Choose whether to upgrade or set the quality factor for the
            // primary language.
            $langArr[$newLang] = (isset($langArr[$newLang])) ?
                max($langArr[$newLang], $newQual) : $newQual;
        }

        // sort list based on value
        // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
        arsort($langArr, SORT_NUMERIC);

        // The languages the client accepts in order of preference.
        $acceptedLanguages = array_keys($langArr);

        // Set the most preferred language that we have a translation for.
        foreach ($acceptedLanguages as $preferredLanguage) {
            if (in_array(mb_strtolower($preferredLanguage), $websiteLanguages)) {
                return strtolower($preferredLanguage);
            }
        }

        return 'en';
    }
}
