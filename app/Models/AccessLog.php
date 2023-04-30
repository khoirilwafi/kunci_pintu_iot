<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessLog extends Model
{
    use HasFactory;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'access_logs';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['user_id', 'door_id', 'office_id', 'log'];
    protected $hidden   = [];
    protected $cast     = [];

    // relation to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relation to door
    public function door()
    {
        return $this->belongsTo(Door::class);
    }

    // relation to office
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
