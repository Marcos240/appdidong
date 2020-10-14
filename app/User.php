<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'passcode',
        'passcodeConfirm',
        'passcodeChangeAt',
        'name',
        'phone',
        'email',
        'pointCollected',
        'pointUsable',
        'passcodeChangeAt'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'passcode', 'passcodeConfirm','passcodeChangeAt'
    ];

    public function bill()
    {
        return $this->hasMany('App\Bill');
    }
    public function liked_user_item()
    {
        return $this->belongsToMany('App\Item');
    }
    public function chosen_item_item()
    {
        return $this->hasMany('App\Item');
    }

    // Events
    public static function boot() {
        parent::boot();

        self::saving(function($user) {
            self::handlePasscode($user);
        });
    }

    // Query Scopes
    public static function scopeFields($query) {
        return $query->addSelect('id', 'username', 'name', 'phone', 'email','pointCollected','pointUsable');
    }


    private static function handlePasscode($user) {
        if ($user->wasChanged('passcode') || $user->isDirty('passcode') || !$user->exists) {
            $user->passcode = Hash::make($user->passcode, [
                'rounds' => 12
            ]);
            $user->passcodeConfirm = Hash::make($user->passcodeConfirm, [
                'rounds' => 12
            ]);
            $user->passcodeChangeAt = Carbon::now();
            $user->pointCollected = 0;
            $user->pointUsable = 0;
        }
    }
}
