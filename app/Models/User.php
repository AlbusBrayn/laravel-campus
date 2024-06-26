<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Multicaret\Acquaintances\Traits\CanBeFollowed;
use Multicaret\Acquaintances\Traits\CanFollow;
use Multicaret\Acquaintances\Traits\Friendable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Friendable;
    use CanFollow, CanBeFollowed;

    /**
     * The attributes that are mass assignable....
     *
     * @var array<int, string>
     */
   
     protected $fillable = [
        'one_signal',
        'name',
        'email',
        'password',
        'is_banned',
        'hide_location',
        'status',
        'school_id',
        'is_muted',
        'otp_code',
        'is_admin',
        'is_active',
        'otp_reset_time',
        'forget_code',
        'forget_expire',
        'lat',
        'lng',
        'device_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'school_id',
        'is_banned',
        'hide_location',
        'is_muted',
        'otp_code',
        'is_admin',
        'otp_reset_time',
        'forget_code',
        'forget_expire'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function avatar(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Avatar::class);
    }

    public function major(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserMajor::class);
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }
}
