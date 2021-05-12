<?php

namespace App\Helpers;

use App\Models\SiteSettings;
use App\User;
use Illuminate\Support\Facades\Schema;

class SiteSettingsHelper
{
    public static $settingsLoaded = false;

    public static $configMap = [
        //App config
        'app.name' => 'SITE_NAME',
        'app.url' => 'SITE_URL',
        'app.copyright' => 'SITE_COPYRIGHT',

        //Email config
        'mail.driver' => 'MAIL_DRIVER',
        'mail.host' => 'MAIL_HOST',
        'mail.port' => 'MAIL_PORT',
        'mail.username' => 'MAIL_USERNAME',
        'mail.password' => 'MAIL_PASSWORD',
        'mail.from.address' => 'MAIL_FROM_ADDRESS',
        'mail.from.name' => 'MAIL_FROM_NAME',
        'mail.encryption' => 'MAIL_ENCRYPTION',

        //Bitcoin config
        'bitcoind.user' => 'BITCOIND_USER',
        'bitcoind.password' => 'BITCOIND_PASSWORD',
        'bitcoind.host' => 'BITCOIND_HOST',
        'bitcoind.port' => 'BITCOIND_PORT',
        'blockchain.bitcoin_confirmations' => 'BITCOIND_CONFIRMATIONS_REQUIRED',

        //AWS S3 assets bucket conf
        'filesystems.disks.s3.key' => 'S3_KEY',
        'filesystems.disks.s3.secret' => 'S3_SECRET',
        'filesystems.disks.s3.region' => 'S3_REGION',
        'filesystems.disks.s3.bucket' => 'S3_BUCKET',

        //AWS S3 backup bucket conf
        'filesystems.disks.s3b.key' => 'S3_KEY',
        'filesystems.disks.s3b.secret' => 'S3_SECRET',
        'filesystems.disks.s3b.region' => 'S3_REGION',
        'filesystems.disks.s3b.bucket' => 'S3_BUCKET_BACKUP',

        //Google Recaptcha conf
        'captcha.secret' => 'NOCAPTCHA_SECRET',
        'captcha.sitekey' => 'NOCAPTCHA_SITEKEY',
    ];

    public static function loadSettings()
    {
        /*
         * We need to check if site settings table exists before loading them .
         *
         * Since we load the settings in service provider, initial php artisan migrate command on first deploy phase and
         * and phpunit test runs fails when site_settings table is missing. During tests and initial setup, values in the .env file are going to be used instead.
         */
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        if (self::$settingsLoaded) {
            return;
        }

        $settings = SiteSettings::all()->pluck('sensitive', 'name')->all();

        $values = [];

        foreach (self::$configMap as $key => $name) {
            if (isset($settings[$name]) && strlen(trim($settings[$name])) > 0) {

                //if the value is set via .env file and it is now set via db config, keep using the value in .env file
                //this is required for the initial setup should work properly and
                //it is also required to use the config values from .env file running phpunit tests.
                if (isset($_ENV[$name]) && ! empty($_ENV[$name]) && empty($settings[$name])) {
                    continue;
                }
                $values[$key] = $settings[$name];
            }
        }

        self::$settingsLoaded = true;
    }

    public static function getPublicSettings()
    {
        $keys = [
            'SITE_NAME',
            'SITE_URL',
            'SITE_LOGO',
            'SITE_LOGO_INVERT',
            'SITE_CONTACT_EMAIL',
            'SITE_COPYRIGHT',
            'SOCIAL_FACEBOOK_URL',
            'SOCIAL_TWITTER_URL',
        ];

        $settings = SiteSettings::select([
            'name',
            'value',
            'type',
        ])->whereIn('name', $keys)->get();

        $map = [];
        foreach ($settings as $setting) {
            $map[$setting->name] = [
                'value' => $setting->value,
                'file_url' => $setting->file_url,
            ];
        }

        foreach ($keys as $key) {
            //Check if config is found in DB,
            if (! isset($map[$key])) {
                //Search for existing values coming from .env file
                $configPath = array_search($key, self::$configMap);

                if ($configPath !== false && config($configPath, 'no-value' !== 'no-value')) {
                    //use that value if exists
                    $map[$key] = [
                        'value' => config($configPath),
                        'file_url' => null,
                    ];
                } //Use empty values if not exists
                else {
                    $map[$key] = [
                        'value' => null,
                        'file_url' => null,
                    ];
                }
            }
        }

        return $map;
    }

    public static function get($key)
    {
        $setting = SiteSettings::where('name', $key)->first();
        if ($setting) {
            return [
                'value' => $setting->sensitive,
                'file_url' => $setting->file_url,
            ];
        } else {
            return [
                'value' => '',
                'file_url' => '',
            ];
        }
    }

    public static function getFavicon()
    {
        $favicon = self::get('SITE_FAVICON');

        return $favicon['file_url'] ? $favicon['file_url'] : '/images/favicon.ico';
    }

    /**
     * Copy a file, or recursively copy a folder and its contents.
     * @author      Aidan Lister <aidan@php.net>
     * @version     1.0.1
     * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
     * @param       string $source Source path
     * @param       string $dest Destination path
     * @param       int $permissions New folder creation permissions
     * @return      bool     Returns true on success, false on failure
     */
    public static function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (! is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            self::xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();

        return true;
    }

    public static function rrmdir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src.'/'.$file;
                if (is_dir($full)) {
                    self::rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    public static function generateRefCode($user) {

        if($user->ref) return $user->ref;

        $number = mt_rand(1000000000, 9999999999); // better than rand()

        $model = User::whereRefcode($number)->first();

        // call the same function if the barcode exists already
        if ($model) {
            return self::generateRefCode($user);
        }

        // store ref code
        $user->refcode = $number;
        $user->save();

        // otherwise, it's valid and can be used
        return $number;
    }

    public static function getUserByReferral($code) {

        if(!$code) return null;

        if($model = User::whereRefcode($code)->first()) {
            return $model->id;
        }

        return null;
    }
}
