<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstablishmentApplications extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "establishment_applications";

    protected $fillable = [
        'id',
        'establishment_name',
        'establishment_address',
        'permit_no',
        'food_type',
        'telephone',
        'alt_telephone',
        'email',
        'trn',
        'zone',
        'establishment_category_id',
        'prev_est_closed',
        'current_est_closed',
        'closure_date',
        'sign_off_status',
        'reprint',
        'application_date',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'new_est'
    ];

    public $timestamps = true;

    public function payment(): HasOne
    {
        return $this->hasOne(Payments::class, 'application_id', 'id')->where('application_type_id', 3);
    }

    public function establishmentCategory(): HasOne
    {
        return $this->hasOne(EstablishmentCategories::class, 'id', 'establishment_category_id');
    }

    public function operators(): HasMany
    {
        return $this->hasMany(FoodEstablishmentOperators::class, 'establishment_application_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function testResults(): HasOne
    {
        return $this->hasOne(TestResult::class, 'application_id', 'id')->where('application_type_id', '3');
    }

    public function signOff(): HasOne
    {
        return $this->hasOne(SignOff::class, 'application_id', 'id')->where('application_type_id', '3');
    }
}
