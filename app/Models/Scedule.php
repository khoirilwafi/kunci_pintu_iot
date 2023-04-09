<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scedule extends Model
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

    protected $fillable = ['name', 'user_id', 'door_id', 'date_running', 'time_begin', 'time_end', 'is_repeating', 'day_0', 'day_1', 'day_2', 'day_3', 'day_4', 'day_5', 'day_6', 'status'];

    protected $cast = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scedule()
    {
        return $this->hasMany(Scedule::class);
    }
}
