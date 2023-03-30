<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScedulePivot extends Model
{
    use HasFactory;

    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'scedule_pivots';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    protected $hidden = [];

    protected $fillable = ['door_id', 'scedule_id'];

    protected $cast = [];

    public function scedule()
    {
        return $this->belongsTo(Scedule::class);
    }

    public function door()
    {
        return $this->belongsTo(Door::class);
    }
}
