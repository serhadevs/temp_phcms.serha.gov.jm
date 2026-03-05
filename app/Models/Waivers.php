<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waivers extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = "waivers";

    protected $guarded = [];

    public function establishment()
    {
        return $this->belongsTo(WaiverEstablishments::class, 'waiver_establishment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function estwithWaivers()
    {
        return $this->belongsTo(EstablishmentClinics::class, 'application_id', 'id');
    }

}
