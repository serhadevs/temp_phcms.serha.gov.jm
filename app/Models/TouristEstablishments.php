<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TouristEstablishments extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function signOffs():HasOne{
        return $this->hasOne(SignOff::class,'application_id', 'id')->where('application_type_id', 6);
    }

    public function testResults():HasOne{
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', 6);
    }

    public function payments():HasOne{
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', 6);
    }

    public function printableApplication():HasOne{
        return $this->hasOne(PrintableApplications::class, 'application_id', 'id');
    }
}
