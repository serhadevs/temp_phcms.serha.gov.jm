<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class FoodEstablishmentOperators extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "food_est_operators";

    protected $fillable =[
        'id',
        'establishment_application_id',
        'name_of_operator',
        'created_at',
        'updated_at',
        // Needs to be added to live table
        'deleted_at'
    ];

    public $timestamps = true;
}
