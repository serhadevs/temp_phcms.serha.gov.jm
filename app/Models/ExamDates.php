<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamDates extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "exam_dates";
    protected $fillable = [
        'id',
        'facility_id',
        'permit_category_id',
        'application_type_id',
        'exam_day',
        'exam_start_time',
        'exam_site_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function examSites():HasOne{
        return $this->hasOne(ExamSites::class, 'id', 'exam_site_id');
    }

    public function permitCategory():HasOne{
        return $this->hasOne(PermitCategory::class, 'id', 'permit_category_id');
    }
}
