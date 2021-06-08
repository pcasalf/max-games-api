<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Soulcodex\Keyable\Keyable;

/**
 * Class User
 * @package App\Models
 * @property string name
 * @property string last_name
 * @property string email
 * @property Carbon birthday
 * @property Carbon verified_at
 * @property Collection cart
 */
class User extends Authenticatable
{
    use Notifiable, Keyable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'birthday',
        'is_admin',
        'verification_token',
        'verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean'
    ];

    /**
     * Generate a secure unique API key
     *
     * @return string
     */
    public static function generateVerificationToken()
    {
        do {
            $key = Str::uuid();
        } while (self::keyExists($key));

        return $key;
    }

    /**
     * Check if a key already exists
     *
     * Includes soft deleted records
     *
     * @param string $key
     * @return bool
     */
    public static function keyExists($key)
    {
        return User::withTrashed()
            ->where('verification_token', $key)
            ->exists();
    }

    /**
     * @return HasMany
     */
    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }
    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
}

