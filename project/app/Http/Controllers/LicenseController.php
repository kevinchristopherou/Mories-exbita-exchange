<?php

namespace App\Http\Controllers;

use App\Helpers\SiteSettingsHelper;
use App\Http\Requests\LicenseRequest;
use App\Services\LicenseManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LicenseController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(config('settings.LICENSE_ACTIVATED') == "installed" && config('settings.LICENSE_ACTIVATED_URL') == $request->getHttpHost()) {
            return response()->redirectTo('/');
        }

        return view('license')->with('favicon', SiteSettingsHelper::getFavicon());
    }

    public function activate(LicenseRequest $request)
    {
        LicenseManager::activate($request->get('license'), $request->getHttpHost());

        // Clear config cache
        Artisan::call('config:cache');

        // Add some delay
        sleep(3);

        return response()->redirectToRoute('home');
    }
}
