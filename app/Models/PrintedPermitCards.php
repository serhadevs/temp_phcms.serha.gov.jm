<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintedPermitCards extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'zipped_applications';

    protected $guarded = [];


    public $timestamps = true;
}
