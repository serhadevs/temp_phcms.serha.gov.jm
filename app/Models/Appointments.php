<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "appointments";

    protected $fillable = [
        'id',
        'appointment_date',
        'facility_id',
        'permit_application_id',
        'health_cert_application_id',
        'exam_date_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function applications(): BelongsTo
    {
        return $this->belongsTo(PermitApplication::class, 'permit_application_id', 'id');
    }

    public function examDate(): HasOne
    {
        return $this->hasOne(ExamDates::class, 'id', 'exam_date_id')->withTrashed();
    }

    // public function examDate(): HasOne
    // {
    //     return $this->hasOne(ExamDates::class, 'id', 'exam_date_id');
    // }

    public function examSites(): HasOne
    {
        return $this->hasOne(ExamSites::class, 'facility_id', 'facility_id')->withTrashed();
    }

    public function testSites(): HasOne{
        return $this->hasOne(ExamSites::class,'id','facility_id');
    }

    public function examSitesId():HasOne{
        return $this->hasOne(ExamSites::class,'id','exam_site_id');
    }
    public function permitCategory(): HasOne{
        return $this->hasOne(PermitCategory::class,'id','permit_category_id');
    }

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('system_operation_type_id', 6);
    }

    public function signOff(): HasOne{
        return $this->hasOne(SignOff::class,'application_id','permit_application_id');
    }

   


}
