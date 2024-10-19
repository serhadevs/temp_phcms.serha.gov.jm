<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectedCards extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "collected_cards";
    protected $guarded = [];

    public $timestamps = true;


}
