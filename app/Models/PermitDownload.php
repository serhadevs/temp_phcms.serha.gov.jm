<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PermitDownload extends Model
{
    protected $fillable = [
        'token', 'establishment_clinic_id', 'status',
        'file_path', 'file_name', 'error_message', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->token = $model->token ?? (string) Str::uuid();
        });
    }
}
