<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTypes extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'email_types';

   protected $guarded = [];

   
}
