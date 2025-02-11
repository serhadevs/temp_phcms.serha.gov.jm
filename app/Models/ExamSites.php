<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSites extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
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

    public function facility():BelongsTo{
        return $this->belongsTo(Facility::class,'facility_id','id');
    }


}
