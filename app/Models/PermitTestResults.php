<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitTestResults extends Model
{
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

}
