<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class SettingsManager
{
    /**
     * Update env file
     * @param string $key
     * @param string $value
     */
    public function envUpdate($key, $value)
    {
        // Get env file path
        $path = base_path('.env');
        $content = file_get_contents($path);

        // Check if file exists
        if (file_exists($path)) {

            $forbiddenChars = ['\\', '"'];

            foreach ($forbiddenChars as $char) {
                $value = str_replace("$char", '', $value);
            }

            $value = htmlspecialchars_decode(strip_tags($value));

            $env = config('settings.'.$key);

            // Check if env key exists (if yes update the value, otherwise create a new key)
            if(mb_strpos($content, $key . "=") !== false) {

                // Check if existing env value contains whitespaces
                $env = "\"{$env}\"";

                // Update existing env value
                file_put_contents($path, str_replace(
                    $key . '=' . $env, $key . '=' . "\"{$value}\"", $content
                ));
            } else {

                $content .= $key . '=' . "\"{$value}\"" . PHP_EOL;

                file_put_contents($path, $content);
            }
        }


    }

    /**
     * Get env value
     * @param string $key
     * @return string
     */
    public function envValue($key)
    {
        if(!$key) return '';

        return config('settings.'.$key);
    }
}

