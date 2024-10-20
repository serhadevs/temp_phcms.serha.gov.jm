<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class ExamDates extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "exam_dates";
    protected $guarded = [];

    public function examSites(): HasOne
    {
        return $this->hasOne(ExamSites::class, 'id', 'exam_site_id')->withTrashed();
    }

    public function permitCategory(): HasOne
    {
        return $this->hasOne(PermitCategory::class, 'id', 'permit_category_id');
    }
    
    public function facility():BelongsTo
    {
        return $this->belongsTo(Facility::class,'facility_id','id');
    }

    public function application_type():BelongsTo{
        return  $this->belongsTo(ApplicationType::class,'application_type_id','id');
    }

    public function availableSites(): HasOne
    {
        return $this->hasOne(ExamSites::class, 'id', 'exam_site_id');
    }

}
