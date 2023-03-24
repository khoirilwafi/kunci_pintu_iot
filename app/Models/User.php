<?php

namespace App\Models;

use App\Notifications\resetPassword;
use Ramsey\Uuid\Uuid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    protected $fillable = ['email', 'phone', 'name', 'gender', 'password', 'role', 'added_by'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function office()
    {
        return $this->hasOne(Office::class);
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class);
    }

    // public function sendPasswordResetNotification($token)
    // {
    //     $url = 'https://' . request()->getHttpHost() . '/reset-password?token=' . $token;

    //     $this->notify(new resetPassword($url));
    //     // return (new resetPassword())->toMail(request()->user());
    // }
}
