<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointments extends Model
{
    use HasFactory;

    protected $table = "appointments";

    protected $fillable = [
        'id',
        'appointment_date',
        'facility_id',
        'permit_application_id',
        'health_cert_application_id',
        'exam_date_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function applications():BelongsTo{
        return $this->belongsTo(PermitApplication::class, 'permit_application_id', 'id');
    }
}
