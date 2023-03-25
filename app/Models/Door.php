<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Door extends Model
{
    use HasFactory;

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

    protected $fillable = ['name', 'status', 'is_lock', 'office_id', 'device_id', 'socket_id'];

    protected $cast = [];

    public function office()
    {
        return $this->hasOne(Office::class);
    }
}
