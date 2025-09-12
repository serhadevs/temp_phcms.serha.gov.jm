<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class TestResult extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    // use Loggable;

    protected $table = "test_results";

    protected $fillable = [
        'id',
        'application_type_id',
        'application_id',
        'test_location',
        'staff_contact',
        'test_date',
        'comments',
        'critical_score',
        'overall_score',
        'user_id',
        'facility_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'visit_purpose',
        'number_employees',
        'number_emp_permits',
    ];

    public $timestamps = true;

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('system_operation_type_id', 3);
    }

    public function establishmentApplication(): BelongsTo
    {
        return $this->belongsTo(EstablishmentApplications::class, 'application_id', 'id');
    }

    public function swimmingPool(): BelongsTo
    {
        return $this->belongsTo(SwimmingPoolsApplications::class, 'application_id', 'id');
    }

    public function touristEstablishment(): BelongsTo
    {
        return $this->belongsTo(TouristEstablishments::class, 'application_id', 'id');
    }

    public function healthCertApplication(): BelongsTo
    {
        return $this->belongsTo(HealthCertApplications::class, 'application_id', 'id');
    }

    public function permitApplication(): BelongsTo
    {
        return $this->belongsTo(PermitApplication::class, 'application_id', 'id');
    }
}
