<?php

namespace App;

use App\Helpers\SiteSettingsHelper;
use App\Models\FiatWallet;
use App\Models\ReferralEarning;
use App\Models\SmsLog;
use App\Models\Wallet;
use App\Traits\FieldUpdateTrait;
use App\Traits\HasWalletsTrait;
use App\Traits\UserPassportTrait;
use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Jedrzej\Searchable\SearchableTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, UserPassportTrait, HasRolesAndPermissions, HasWalletsTrait, FieldUpdateTrait, SearchableTrait;

    protected $searchable = ['id', 'email', 'name', 'is_blocked', 'id_verified'];

    protected $dates = [
        'mail_sent_at',
        'last_password_reset',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verify_token', 'two_fa_secret', 'status', 'locale', 'id_verified', 'mail_sent_at', 'refcode', 'referrer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verify_token', 'two_fa_secret', 'two_fa_otp', 'last_password_reset', 'mail_sent_at'
    ];

    public function getVerificationUrl()
    {
        return url('/#/user/'.$this->id.'/verify/'.$this->verify_token);
    }

    public function getDeactivationUrl()
    {
        return url('/#/user/'.$this->id.'/deactivate/'.$this->verify_token);
    }

    public function getLocaleAttribute()
    {
        return $this->attributes['locale'] ? $this->attributes['locale'] : 'en';
    }

    public static function generateToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function lastSms()
    {
        return $this->hasOne(SmsLog::class)->latest();
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)
            ->whereHas('currency', function ($query) {
                $query->where('maintenance', 0);
            });
    }

    public function fiatWallets()
    {
        return $this->hasMany(FiatWallet::class)
            ->whereHas('currency');
    }

    public function idDocuments()
    {
        return $this->hasOne(UserIdDocument::class)->latest();
    }

    public function waitingIdDocuments()
    {
        return $this->hasOne(UserIdDocument::class)->where('status', 'waiting');
    }

    public function generateRefcode()
    {
        return SiteSettingsHelper::generateRefCode($this);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer');
    }

    public function referralEarnings()
    {
        return $this->hasMany(ReferralEarning::class, 'user_id');
    }

    public static function getGroups($user)
    {
        $roles = $user->roles->pluck('name')->all();

        if (! $roles) {
            return __('User');
        }

        return implode(',', $user->roles->pluck('name')->all());
    }


}
