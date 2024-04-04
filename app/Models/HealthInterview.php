<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthInterview extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'health_interviews';

    protected $fillable = [

        'permit_application_id',
        'health_cert_application_id',
        'literate',
        'typhoid',
        'lived_abroad',
        'lived_abroad_location',
        'lived_abroad_date',
        'travel_abroad',
        'whitlow',
        'hands_condition',
        'fingernails_condition',
        'teeth_condition',
        'tests_recommended',
        'tests_results',
        'doctor_name',
        'doctor_address',
        'doctor_tele',
        'sign_off_status',
        'user_id',
        'facility_id',

    ];

    public $timestamp = true;

    public function healthInterviewSymptom():HasMany{
        return $this->hasMany(HealthInterviewSymptom::class, 'health_interview_id', 'id');
    }

    public function healthCertApplication():HasOne{
        return $this->hasOne(HealthCertApplications::class, 'id', 'health_cert_application_id');
    }

    public function permitApplication():HasOne{
        return $this->hasOne(PermitApplication::class, 'id', 'permit_application_id');
    }
}
