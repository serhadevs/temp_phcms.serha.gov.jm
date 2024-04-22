<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class TravelHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "travel_history";

    protected $fillable = [
        'id',
        'permit_application_id',
        'health_cert_application_id',
        'destination',
        'travel_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
