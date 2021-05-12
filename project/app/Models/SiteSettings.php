<?php

namespace App\Models;

use App\Services\SettingsManager;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    public $timestamps = false;

    protected $sensitive = '';

    protected $settingsManager;

    protected $fillable = ['key', 'value', 'description', 'label'];

    protected $appends = ['file_url'];

    public function __construct(array $attributes = [])
    {
        $this->settingsManager = new SettingsManager();
        parent::__construct($attributes);
    }

    public function getFileUrlAttribute()
    {
        if (! $this->value) {
            return;
        }
        if ($this->type === 'image' || $this->type === 'file') {
            $region = config('filesystems.disks.s3.region');
            $bucket = config('filesystems.disks.s3.bucket');

            return $this->value;
            // Demo version doesn't allow to upload images to aws
            //return "https://s3.$region.amazonaws.com/$bucket/".$this->value;
        }
    }

    /**
     * Get the settings value
     *
     * @param  string  $value
     * @return string
     */
    public function getValueAttribute($value)
    {
        $value = $this->settingsManager->envValue($this->name);

        return $value && $this->is_sensitive ? str_repeat('*', 20) : $value;
    }

    /**
     * Get the settings value (sensitive)
     *
     * @param  string  $value
     * @return string
     */
    public function getSensitiveAttribute($value)
    {
        return $this->settingsManager->envValue($this->name);
    }
}
