<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'application_type_id',
        'application_id',
        'receipt_no',
        'facility_id',
        'cashier_user_id',
        'amount_paid',
        'total_cost',
        'change_amt',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function applications():BelongsTo{
        return $this->belongsTo(PermitApplication::class, 'application_id', 'id');
    }

    public function facility():HasOne{
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }

    public function applicationType():HasOne{
        return $this->hasOne(ApplicationType::class, 'id', 'application_type_id');
    }

    public function paymentCancellation():HasOne{
        return $this->hasOne(PaymentCancellationRequests::class, 'payment_id', 'id');
    }
}
