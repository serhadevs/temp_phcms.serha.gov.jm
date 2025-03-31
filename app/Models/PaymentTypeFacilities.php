<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTypeFacilities extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "payment_type_facilities";

    protected $fillable = [
        'payment_type_id',
        'facility_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
