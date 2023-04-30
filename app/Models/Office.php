<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;

    // model properties
    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'offices';

    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['name', 'user_id'];
    protected $hidden   = [];
    protected $casts    = [];

    // relation to users table
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relation to schedule
    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
}
