<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Socket extends Model
{
    use HasFactory;

    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    protected $hidden = [];

    protected $fillable = ['app_id', 'type', 'channel'];

    protected $cast = [];

    public function door()
    {
        return $this->hasOne(Door::class);
    }
}
