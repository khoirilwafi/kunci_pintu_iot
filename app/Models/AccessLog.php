<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessLog extends Model
{
    use HasFactory;

    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'access_logs';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    protected $hidden = [];

    protected $fillable = ['user_id', 'door_id', 'office_id', 'log'];

    protected $cast = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function door()
    {
        return $this->belongsTo(Door::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
