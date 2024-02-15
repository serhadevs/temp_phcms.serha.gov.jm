<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristEstablishments extends Model
{
    use HasFactory;

    protected $table = "tourist_establishments";

    protected $fillable = [
        'id',
        'establishment_name',
        'establishment_address',
        'bed_capacity',
        'permit_no',
        'is_eating_establishment',
        'eating_establishment_description',
        'establishment_state',
        'authorized_officer_statement',
        'officer_firstname',
        'officer_lastname',
        'statement_date',
        'sign_off_status',
        'application_date',
        'reprint',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
