<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class SwimmingPoolsApplications extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "swimming_pools_applications";

    protected $fillable = [
        'id',
        'firstname',
        'middlename',
        'lastname',
        'permit_no',
        'swimming_pool_address',
        'application_date',
        'sign_off_status',
        'reprint',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'submitted_by_id'
    ];

    public $timestamps = true;

    public function payment(): HasOne
    {
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', 5);
    }

    public function testResults(): HasOne
    {
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', 5);
    }

    public function signOff(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', 5);
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('application_type_id', 5)
            ->where('system_operation_type_id', 1);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }
}
