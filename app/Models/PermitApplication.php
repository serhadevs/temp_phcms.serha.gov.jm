<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class PermitApplication extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = 'permit_applications';

    protected $fillable = [
        'permit_category_id',
        'establishment_clinic_id',
        'appointment_id',
        'user_id',
        'permit_no',
        'firstname',
        'middlename',
        'lastname',
        'address',
        'date_of_birth',
        'gender',
        'permit_type',
        'cell_phone',
        'home_phone',
        'work_phone',
        'occupation',
        'employer',
        'employer_address',
        'email',
        'trn',
        'applied_before',
        'granted',
        'reason text',
        'photo_upload',
        'sign_off_status',
        'reprint',
        'application_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'no_of_years',
    ];

    public $timestamp = true;

    public function permitCategory(): HasOne
    {
        return $this->hasOne(PermitCategory::class, 'id', 'permit_category_id');
    }

    public function payment(): HasOne
    {
        //This was changed
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', '1');
    }

    public function appointment(): HasMany
    {
        //This was changed
        return $this->hasMany(Appointments::class, 'permit_application_id')->orderBy('created_at', 'desc');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function establishmentClinics(): BelongsTo
    {
        return $this->belongsTo(EstablishmentClinics::class, 'establishment_clinic_id', 'id');
    }

    public function healthInterviews(): HasOne
    {
        return $this->hasOne(HealthInterview::class, 'permit_application_id', 'id');
    }

    public function signOffs(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', '1');
    }

    public function testResults(): HasOne
    {
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', '1');
    }

    public function travelHistory(): HasMany
    {
        return $this->hasMany(TravelHistory::class, 'permit_application_id', 'id');
    }

    public function allUsers(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('system_operation_type_id', 1)
            ->where('application_type_id', 1);
    }
}
