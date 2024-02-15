<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "facilities";

    protected $fillable = [
        'id',
        'name',
        'abbr',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
