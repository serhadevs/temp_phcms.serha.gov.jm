<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class SignOff extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    // use Loggable;

    protected $table = "sign_offs";

    protected $fillable = [
        'id',
        'is_granted',
        'permit_no',
        'refusal_reason',
        'sign_off_date',
        'expiry_date',
        'user_id',
        'application_type_id',
        'application_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function application_type(): HasOne
    {
        return $this->hasOne(ApplicationType::class, 'id', 'application_type_id');
    }

    public function permitApplication(): HasOne
    {
        return $this->hasOne(PermitApplication::class, 'id', 'application_id');
    }

    public function establishmentApplication($value): HasOne
    {
        // dd($value);
        return $this->hasOne(EstablishmentApplications::class, 'id', 'application_id');
    }

    public function estApplication(): HasOne
    {
        return $this->hasOne(EstablishmentApplications::class, 'id', 'application_id');
    }

    public function healthCertApplication(): HasOne
    {
        return $this->hasOne(HealthCertApplications::class, 'id', 'application_id');
    }

    public function swimmingPool(): HasOne
    {
        return $this->hasOne(SwimmingPoolsApplications::class, 'id', 'application_id');
    }

    public function touristEstApplication(): HasOne
    {
        return $this->hasOne(TouristEstablishments::class, 'id', 'application_id');
    }

    public function application($query)
    {
        return $query->when($this->application_type_id == '1', function ($q) {
            return $q->permitApplication;
        });
    }
}
