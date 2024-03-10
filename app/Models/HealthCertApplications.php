<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointments::class, 'health_cert_application_id', 'id');
    }

    public function healthInterviews(): HasOne
    {
        return $this->hasOne(HealthInterview::class, 'health_cert_application_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', "2");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
