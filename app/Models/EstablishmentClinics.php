<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentClinics extends Model
{
    use HasFactory;

    protected $table = "establishment_clinics";

    protected $fillable = [
        'id',
        'name',
        'address',
        'telephone',
        'fax_no',
        'contact_person',
        'no_of_employees',
        'proposed_date',
        'proposed_time',
        'application_date',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;
}
