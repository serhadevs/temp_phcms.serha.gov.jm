<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class PermitTestResults extends Model
{
    use HasFactory;
    use SoftDeletes;
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
        'visit_purpose'
    ];

    public $timestamps = true;

    public function permit_application():HasOne{
        return $this->hasOne(PermitApplication::class, 'id', 'application_id');
    }
}
