<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class Renewals extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'new_application_id',
        'application_type_id',
        'old_application_id',
        'created_at',
        'updated_at'
    ];

    protected $table = 'renewals';

    public $timestamps = true;

    public function permitApplication(): HasOne
    {
        return $this->hasOne(PermitApplication::class, 'id', 'new_application_id');
    }

    public function healthCertApplication(): hasOne
    {
        return $this->hasOne(HealthCertApplications::class, 'id', 'new_application_id');
    }

    public function establishmentApplication(): HasOne
    {
        return $this->hasOne(EstablishmentApplications::class, 'id', 'new_application_id');
    }

    public function swimmingPoolApplication(): HasOne
    {
        return $this->hasOne(SwimmingPoolsApplications::class, 'id', 'new_application_id');
    }

    public function touristEstApplication(): HasOne
    {
        return $this->hasOne(TouristEstablishments::class, 'id', 'new_application_id');
    }

    public function estClinic(): HasOne
    {
        return $this->hasOne(EstablishmentClinics::class, 'id', 'new_application_id');
    }
}
