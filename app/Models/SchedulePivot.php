<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchedulePivot extends Model
{
    use HasFactory;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'door_schedule';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['door_id', 'schedule_id'];
    protected $hidden   = [];
    protected $cast     = [];

    // relation to schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // relation to door
    public function door()
    {
        return $this->belongsTo(Door::class);
    }
}
