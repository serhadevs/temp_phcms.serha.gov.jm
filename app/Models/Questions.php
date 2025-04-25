<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Questions extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'questions';
    public $timestamps = true;
    protected $guarded = [];

    // public function exam()
    // {
    //     return $this->belongsTo(StudentExam::class);
    // }

    public function answers()
    {
        return $this->hasMany(Answers::class, 'question_id');
    }
}
