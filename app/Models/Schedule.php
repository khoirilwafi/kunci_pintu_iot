<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    public $incrementing  = false;
    protected $keyType    = 'string';
    protected $primaryKey = 'id';
    protected $table      = 'schedules';


    // uuid as primary key
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Uuid::uuid4();
        });
    }

    // column setting
    protected $fillable = ['name', 'office_id', 'date_begin', 'date_end', 'time_begin', 'time_end', 'is_repeating', 'day_repeating', 'status'];
    protected $hidden   = [];
    protected $cast     = [];

    // relation to offices table
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    // relation to doors table
    public function door()
    {
        return $this->belongsToMany(Door::class);
    }
}
