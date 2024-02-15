<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbershopHairSalons extends Model
{
    use HasFactory;

    protected $table = "barbershop_hair_salons";
    protected $fillable = [
        'id',
        'establishment_name',
        'est_type',
        'operator',
        'applicant_address',
        'business_address',
        'business_description',
        'telephone',
        'alt_telephone',
        'no_of_employees',
        'user_id	application_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
