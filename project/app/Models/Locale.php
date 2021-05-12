<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $fillable = ['locale', 'name', 'is_active', 'apidocs', 'terms'];

    public function loadFiles()
    {
        $files = array_diff(scandir(resource_path('/lang/'.$this->locale)), ['.', '..']);

        $fileList = [];
        foreach ($files as $file) {
            $path = resource_path('/lang/'.$this->locale.'/'.$file);

            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if ($ext === 'php') {
                $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
                $code = require $path;
                $code = json_encode($code, $options);
            } else {
                $code = file_get_contents($path);
            }

            $fileList[] = [
                'fullname' => $file,
                'name' => str_replace(".$ext", '', $file),
                'contents' => $code,
                'ext' => $ext,
            ];
        }
        $this->setAttribute('files', $fileList);
    }

    public function saveFiles($files)
    {
        foreach ($files as $file) {
            $path = resource_path('/lang/'.$this->locale.'/'.$file['fullname']);

            $contents = json_decode($file['contents'], true);

            //Skip saving if submitted json syntax is invalid
            if ($contents === null) {
                continue;
            }

            //Write json content directly if translation file is json file
            if ($file['ext'] === 'json') {
                file_put_contents($path, $file['contents']);
            } //Export php array as code if translation file is a php file
            elseif ($file['ext'] === 'php') {
                $code = var_export($contents, true);
                $code = "<?php\nreturn\n$code ; \n?>";
                file_put_contents($path, $code);
            }
        }
    }
}
