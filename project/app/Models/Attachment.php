<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use Storage;

class Attachment extends Model
{
    protected $appends = ['signed_url'];

    public function attachable()
    {
        return $this->morphTo();
    }

    private static function fileLocation($attachment, $model = null)
    {
        if ($attachment->attachable_type) {
            $path = explode('\\', $attachment->attachable_type);
            $modelName = array_pop($path);
            $modelId = $attachment->attachable_id;
        } elseif ($model !== null) {
            $modelName = with(new ReflectionClass($model))->getShortName();
            $modelId = $model->id;
        }

        return 'public/attachments/'.$modelName.'/'.intval($modelId) % 10000 .'/'.$modelId.'/'.$attachment->id.'/'.$attachment->file_name;
    }

    public static function getTemporaryFilePath($name = '')
    {
        return storage_path().'/app/uploadtmp/'.$name;
    }

    public static function attachTemporaryUpload($fileMeta, $model, $public = true)
    {
        $attachment = new self();
        $attachment->file_name = self::generateName($fileMeta['name']);
        $attachment->file_size = $fileMeta['size'];
        $attachment->file_type = $fileMeta['type'];
        $attachment->public = $public;

        $model->attachments()->save($attachment);

        $contents = file_get_contents(self::getTemporaryFilePath($fileMeta['tmpName']));

        $destPath = self::fileLocation($attachment, $model);

        Storage::disk(self::getStorage())->put($destPath, $contents);

        return $attachment;
    }

    public static function attachWithContents($name, $content, $type, $model)
    {
        $attachment = new self();
        $attachment->file_name = self::generateName($name);
        $attachment->file_size = mb_strlen($content, '8bit');
        $attachment->file_type = $type;

        $model->attachments()->save($attachment);

        $destPath = self::fileLocation($attachment, $model);

        Storage::disk(self::getStorage())->put($destPath, $content);

        return $attachment;
    }

    public function exists()
    {
        return Storage::disk(self::getStorage())->exists($this->filePath);
    }

    public function unlink()
    {
        if (Storage::disk(self::getStorage())->exists($this->filePath)) {
            Storage::disk(self::getStorage())->delete($this->filePath);
        }
    }

    public function getFilePathAttribute()
    {
        return self::fileLocation($this);
    }

    /*
     * Retrieves a signed aws s3 url for the attachment to allow users to download it.
     */

    /**
     * @return string
     */
    public function getSignedUrlAttribute()
    {
        $attachment = $this;

        if(!config('settings.STORAGE_STATE')) {
            return Storage::disk($this->storage)->url($attachment->filePath);
        }

        //Return the cached signed url of the attachment
        return Cache::remember('attachment_signed_url_'.$this->id, config('cache.lifetime.aws_signed_urls'), function () use ($attachment) {

            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = '+1 week';

            $command = $client->getCommand('GetObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $attachment->filePath,
            ]);

            $request = $client->createPresignedRequest($command, $expiry);

            return (string) $request->getUri();
        });
    }

    public function getContentsAttribute()
    {


        return Storage::disk(self::getStorage())->get($this->filePath);
    }

    public static function generateName($filename) {
        $extension = explode('.', $filename);
        return sha1(time() . $filename) . '.' . $extension[count($extension) - 1];
    }

    public static function getStorage() {
        return config('settings.STORAGE_STATE') ? 's3' : 'local';
    }
}
