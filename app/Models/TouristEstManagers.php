<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TouristEstManagers extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tourist_establishment_managers";

    protected $fillable = [
        'id',
        'tourist_establishment_id',
        'firstname',
        'lastname',
        'post_held',
        'qualifications',
        'nationality',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
