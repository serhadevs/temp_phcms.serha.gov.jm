<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZippedApplications extends Model
{
    use HasFactory;

    protected $table = 'zipped_applications';

    protected $fillable = [
        'id',
        'application_type_id',
        'application_id',
        'download_id',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function payment():HasMany{
        return $this->hasMany(Payments::class, 'application_id', 'application_id');
    }
}
