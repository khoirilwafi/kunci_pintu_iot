<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Door extends Model
{
    use HasFactory, HasApiTokens;

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

    protected $hidden = [];

    protected $fillable = ['name', 'is_lock', 'office_id', 'device_id', 'device_key', 'socket_id', 'token', 'ble_data'];

    protected $cast = [];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function scedule()
    {
        return $this->hasMany(Scedule::class);
    }
}
