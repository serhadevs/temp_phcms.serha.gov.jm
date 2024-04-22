<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Renewals extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'new_application_id',
        'application_type_id',
        'old_application_id',
        'created_at',
        'updated_at'
    ];

    protected $tale = 'renewals';

    public $timestamps = true;

    public function permitApplication(): HasOne
    {
        return $this->hasOne(PermitApplication::class, 'id', 'new_application_id');
    }
}
