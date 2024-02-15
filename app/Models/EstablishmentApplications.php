<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentApplications extends Model
{
    use HasFactory;

    protected $table = "establishment_applications";

    protected $fillable = [
        'id',
        'establishment_name',
        'establishment_address',
        'permit_no',
        'food_type',
        'telephone',
        'alt_telephone',
        'email',
        'trn',
        'zone',
        'establishment_category_id',
        'prev_est_closed',
        'current_est_closed',
        'closure_date',
        'sign_off_status',
        'reprint',
        'application_date',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'new_est'
    ];

    public $timestamps = true;
}
