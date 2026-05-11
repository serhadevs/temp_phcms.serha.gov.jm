<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcardAccessHistory extends Model
{
    protected $table = 'ecard_access_history';
    
    public $timestamps = false;
    
    protected $fillable = [
        'sign_off_id',
        'ecard_id',
        // 'user_id',
        'access_type',
        'access_method',
        'ip_address',
        'user_agent',
        'accessed_at'
    ];

    protected $dates = [
        'accessed_at',
        'created_at'
    ];

    public function signOff()
    {
        return $this->belongsTo(SignOff::class);
    }
}