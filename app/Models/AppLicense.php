<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLicense extends Model
{
    protected $table = 'app_licenses';

    protected $fillable = [
        'license_key',
        'product_name',
        'client_name',
        'client_email',
        'max_activations',
        'expires_at'
    ];
}