<?php

namespace App\Http\Controllers;

use App\Helpers\SiteSettingsHelper;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class EthereumStateController extends Controller
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
        if(config('settings.ETHEREUM_STATE') == "verified") {
            return response()->redirectTo('/');
        }

        return view('ethereum-state')->with('favicon', SiteSettingsHelper::getFavicon());
    }
    
    /**
     * Save Ethereum Node state
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        // Set verified state
        $this->settingsManager->envUpdate('ETHEREUM_STATE', "verified");

        // Clear config cache
        Artisan::call('config:cache');

        // Add some delay to clear the cache
        sleep(3);

        return response()->redirectToRoute('home');
    }
}
