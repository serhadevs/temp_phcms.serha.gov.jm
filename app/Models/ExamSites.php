<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSites extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = "exam_sites";

    protected $fillable = [
        'id',
        'facility_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public $timestamps = true;

    public function examDate():BelongsTo{
        return $this->belongsTo(ExamDates::class, 'id', 'exam_sites_id');
    }
}
