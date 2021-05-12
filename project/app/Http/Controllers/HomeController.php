<?php

namespace App\Http\Controllers;

use App\Helpers\SiteSettingsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Locale;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index')
            ->with('admin', false)
            ->with('favicon', SiteSettingsHelper::getFavicon());
    }

    public function admin()
    {
        return view('index')
            ->with('admin', true)
            ->with('favicon', SiteSettingsHelper::getFavicon());
    }

    public function preview()
    {
        return view('index')
            ->with('admin', false)
            ->with('preview', true)
            ->with('favicon', SiteSettingsHelper::getFavicon());
    }

    public function apidocs()
    {
        $content = Locale::where('locale', App::getLocale())->first();

        return $content->apidocs;
    }

    public function terms()
    {
        $content = Locale::where('locale', App::getLocale())->first();

        return $content->terms;
    }

    public function chart(Request $request)
    {
        \Debugbar::disable();

        $tradingView = 'https://saveload.tradingview.com';

        return view('trading-view', $request->all())->with('tradingviewUrl', $tradingView);
    }
}
