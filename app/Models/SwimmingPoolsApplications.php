<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwimmingPoolsApplications extends Model
{
    use HasFactory;

    protected $table = "swimming_pools_applications";

    protected $fillable = [
        'id',
        'firstname',
        'middlename',
        'lastname',
        'permit_no',
        'swimming_pool_address',
        'application_date',
        'sign_off_status',
        'reprint',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
