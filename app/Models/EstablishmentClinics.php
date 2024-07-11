<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class EstablishmentClinics extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "establishment_clinics";

    protected $fillable = [
        'id',
        'name',
        'address',
        'telephone',
        'fax_no',
        'contact_person',
        'no_of_employees',
        'proposed_date',
        'proposed_time',
        'application_date',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function payment(): HasOne
    {
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', 4);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function permits(): HasMany
    {
        return $this->hasMany(PermitApplication::class, 'establishment_clinic_id', 'id');
    }

    public function signOff(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', 3);
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('application_type_id', 4)
            ->where('system_operation_type_id', 1);
    }
}
