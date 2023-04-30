<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Door extends Model
{
    use HasFactory, HasApiTokens;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'doors';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['name', 'is_lock', 'office_id', 'device_name', 'socket_id', 'key'];
    protected $hidden   = ['device_pass'];
    protected $cast     = [];


    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function scedule()
    {
        return $this->hasMany(Scedule::class);
    }
}
