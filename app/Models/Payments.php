<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class Payments extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

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
        'deleted_at',
        'manual_receipt_no',
        'manual_receipt_date'
    ];

    public $timestamps = true;

    public function permitApplications(): BelongsTo
    {
        return $this->belongsTo(PermitApplication::class, 'application_id', 'id');
    }

    public function facility(): HasOne
    {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }

    public function applicationType(): HasOne
    {
        return $this->hasOne(ApplicationType::class, 'id', 'application_type_id');
    }

    public function paymentCancellation(): HasOne
    {
        return $this->hasOne(PaymentCancellationRequests::class, 'payment_id', 'id');
    }

    public function cashier(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'cashier_user_id')->withTrashed();
    }

    public function application_id(): HasOne{
        return $this->hasOne(SignOff::class,'id','application_id');
    }
}
