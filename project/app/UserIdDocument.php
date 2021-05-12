<?php

namespace App;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;

class UserIdDocument extends Model
{
    use SearchableTrait;

    public $searchable = ['id', 'user_id', 'ssid', 'status', 'created_at'];

    protected $with = ['selfiePhoto', 'identityPhoto', 'addressPhoto'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'evaluated_at';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function identityPhoto()
    {
        return $this->belongsTo(Attachment::class, 'identity_photo_id');
    }

    public function selfiePhoto()
    {
        return $this->belongsTo(Attachment::class, 'selfie_photo_id');
    }

    public function addressPhoto()
    {
        return $this->belongsTo(Attachment::class, 'address_photo_id');
    }
}
