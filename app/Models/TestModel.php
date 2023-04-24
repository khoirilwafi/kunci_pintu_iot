<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;

    protected $table    = 'test_table';
    protected $hidden   = [];
    protected $fillable = ['data'];
    protected $cast     = [];
}
