<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCertApplications extends Model
{
    use HasFactory;

    protected $table = "health_cert_applications";

    protected $fillable = [
        'id',
        'firstname',
        'middlename',
        'lastname',
        'address',
        'permit_no',
        'date_of_birth',
        'sex',
        'telephone',
        'trn',
        'email',
        'occupation',
        'employer',
        'employer_address',
        'applied_before',
        'granted',
        'reason',
        'sign_off_status',
        'reprint',
        'application_date',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
