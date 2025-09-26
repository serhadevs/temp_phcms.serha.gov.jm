<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'deleted_at',
        'status'
    ];

    public $timestamps = true;

    public function facility():HasOne{
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }

    public function paymentType():HasOne{
        return $this->hasOne(PaymentTypes::class, 'id', 'payment_type_id');
    }
}
