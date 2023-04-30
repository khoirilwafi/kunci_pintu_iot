<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
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

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'users';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['added_by', 'email', 'phone', 'name', 'gender', 'role', 'avatar'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime'];

    // relation with office table
    public function office()
    {
        return $this->hasOne(Office::class);
    }

    // custom email reset password
    public function sendPasswordResetNotification($token): void
    {
        $url_protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        $url_server   = $_SERVER['SERVER_NAME'];
        $url_port     = $_SERVER['SERVER_PORT'] ? ':' . $_SERVER['SERVER_PORT'] : '';

        $email = $this->getEmailForPasswordReset();
        $url = $url_protocol . $url_server . $url_port . '/reset-password/' . $token . '?email=' . $email;

        $this->notify(new ResetPasswordNotification($url));
    }
}
