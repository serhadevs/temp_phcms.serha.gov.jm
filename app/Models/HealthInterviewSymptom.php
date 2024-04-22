<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class HealthInterviewSymptom extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Loggable;

    protected $table = "health_interview_symptom";

    protected $fillable = [
        'id',
        'health_interview_id',
        'symptom_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function symptoms():HasOne{
        return $this->hasOne(Symptoms::class, 'id', 'symptom_id');
    }
}
