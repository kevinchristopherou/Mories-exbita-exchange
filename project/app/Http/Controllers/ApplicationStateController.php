<?php

namespace App\Http\Controllers;

use App\Helpers\SiteSettingsHelper;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ApplicationStateController extends Controller
{
    protected $settingsManager;

    public function __construct()
    {
        $this->settingsManager = new SettingsManager();
    }

    /**
     * Check connection with Ethereum Node
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(config('settings.APPLICATION_STATE') == "verified") {
            return response()->redirectTo('/');
        }

        $license = config('settings.APPLICATION_CODE');
        $validator = config('settings.LICENSE_VALIDATOR');

        $link = $validator . '/wp-json/exbita-files/download/' . $license;

        return view('application-state')->with('favicon', SiteSettingsHelper::getFavicon())->with('link', $link);
    }

    /**
     * Save Ethereum Node state
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        // Set verified state
        $this->settingsManager->envUpdate('APPLICATION_STATE', "synced");

        // Clear config cache
        Artisan::call('config:cache');

        // Add some delay to clear the cache
        sleep(3);

        return response()->redirectToRoute('home');
    }
}
