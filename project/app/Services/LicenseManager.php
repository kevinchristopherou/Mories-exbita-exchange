<?php

namespace App\Services;

class LicenseManager
{
    public static function activate($license, $host)
    {
        $settingsManager = new SettingsManager();

        // Activate license and store domain name
        $settingsManager->envUpdate('LICENSE_ACTIVATED', "installed");
        $settingsManager->envUpdate('LICENSE_ACTIVATED_URL', $host);

        // Set license code
        $settingsManager->envUpdate('APPLICATION_CODE', $license);
    }

    public static function deactivate()
    {
        $settingsManager = new SettingsManager();
        $settingsManager->envUpdate('LICENSE_ACTIVATED', "");
        $settingsManager->envUpdate('APPLICATION_CODE', "");
    }
}