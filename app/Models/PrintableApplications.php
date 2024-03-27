<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintableApplications extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'printable_applications';

    protected $fillable = [
        'id',
        'application_id',
        'application_type_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function touristEstablishment():HasOne{
        return $this->hasOne(TouristEstablishments::class, 'id', 'application_id')->where('application_type_id', 6);
    }
}
