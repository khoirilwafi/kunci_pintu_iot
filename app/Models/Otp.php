<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'otps';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['user_id', 'code_otp', 'valid_until'];
    protected $hidden   = [];
    protected $cast     = [];

    // relation to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
