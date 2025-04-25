<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StudentExam extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'student_exams';
    public $timestamps = true;
    protected $guarded = [];


    public function questions(): HasMany
    {
        return $this->hasMany(Questions::class, 'exam_id', 'id');
    }

    public function getQuestionCountAttribute()
    {
        return $this->questions()->count();
    }
}
