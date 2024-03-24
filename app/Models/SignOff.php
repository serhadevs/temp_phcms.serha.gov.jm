<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SignOff extends Model
{
    use HasFactory;

    protected $table = "sign_offs";

    protected $fillable = [
        'id',
        'is_granted',
        'permit_no',
        'refusal_reason',
        'sign_off_date',
        'expiry_date',
        'user_id',
        'application_type_id',
        'application_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function user():HasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
