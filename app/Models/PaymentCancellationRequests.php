<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;

class PaymentCancellationRequests extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;

    protected $table = "payment_cancellation_requests";

    protected $fillable = [
        "id",
        "payment_id",
        "reason",
        "approved",
        "requester_user_id",
        "facility_id",
        "approver_user_id",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public $timestamps = true;

    public function payment():HasOne{
        return $this->hasOne(Payments::class, 'id', 'payment_id');
    }

    public function requester():HasOne{
        return $this->hasOne(User::class, 'id', 'requester_user_id');
    }
}
