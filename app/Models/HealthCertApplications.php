<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class HealthCertApplications extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;
    use \OwenIt\Auditing\Auditable;

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
        'deleted_at',
        'submitted_by_id'
    ];

    public $timestamps = true;

    public function appointment(): HasMany
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
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function signOff(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', 2);
    }

    public function testResults(): HasOne
    {
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', 2);
    }

    public function travelHistory(): HasMany
    {
        return $this->hasMany(TravelHistory::class, 'health_cert_application_id', 'id');
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('application_type_id', 2)
            ->where('system_operation_type_id', 1);
    }
}
