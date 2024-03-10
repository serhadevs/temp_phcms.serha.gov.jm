<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthInterviewSymptom extends Model
{
    use HasFactory;
    use SoftDeletes;

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
