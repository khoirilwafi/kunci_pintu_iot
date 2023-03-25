<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Access extends Model
{
    use HasFactory;

    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'access';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    protected $hidden = [];

    protected $fillable = ['name', 'user_id', 'door_id', 'is_temporary', 'begin_at', 'end_at'];

    protected $cast = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function door()
    {
        return $this->belongsTo(Door::class);
    }
}
