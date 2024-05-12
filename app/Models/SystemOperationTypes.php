<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemOperationTypes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "system_operation_types";

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
