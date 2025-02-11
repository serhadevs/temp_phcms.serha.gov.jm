<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class TouristEstablishments extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    // use Loggable;

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

    public function signOffs(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', 6);
    }

    public function testResults(): HasOne
    {
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', 6);
    }

    public function payments(): HasOne
    {
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', 6);
    }

    public function printableApplication(): HasOne
    {
        return $this->hasOne(PrintableApplications::class, 'application_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function managers(): HasMany
    {
        return $this->hasMany(TouristEstManagers::class, 'tourist_establishment_id', 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(TouristEstServices::class, 'tourist_establishment_id', 'id');
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('system_operation_type_id', 1)
            ->where('application_type_id', 6);
    }
}
