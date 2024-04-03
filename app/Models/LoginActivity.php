<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;
    protected $table = "login_location";

    protected $fillable = [
        'login_time',
        'logout_time',
        'user_agent',
        'platform',
        'user_id',
        'facility_id',
        'session_id',
        'ip_address'
    ];

    public $timestamps = true;
}
