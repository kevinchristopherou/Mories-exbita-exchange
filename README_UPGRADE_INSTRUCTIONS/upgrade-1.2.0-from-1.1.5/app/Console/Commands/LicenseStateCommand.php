<?php

namespace App\Console\Commands;

use App\Services\LicenseManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LicenseStateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'License State Check';

    public $currency;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $license = config('settings.APPLICATION_CODE');
        $state = config('settings.LICENSE_ACTIVATED');

        if(!$state) {
            return true;
        }

        $response = json_decode(file_get_contents(config("settings.LICENSE_VALIDATOR") . "/wp-json/exbita-license-status/{$license}"));

        if($response && $response->status && $response->status == "deactivated") {
            LicenseManager::deactivate();

            // Clear config cache
            Artisan::call('config:cache');
        }
    }
}
