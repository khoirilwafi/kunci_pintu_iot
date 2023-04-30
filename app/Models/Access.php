<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Access extends Model
{
    use HasFactory;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'access';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['name', 'user_id', 'door_id', 'is_temporary', 'time_begin', 'time_end', 'date_begin', 'date_end', 'is_remote', 'is_running'];
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
}
