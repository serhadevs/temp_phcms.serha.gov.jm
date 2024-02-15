<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationType extends Model
{
    use HasFactory;

    protected $table = 'application_types';

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
