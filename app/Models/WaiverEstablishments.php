<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaiverEstablishments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "waiver_establishments";

    protected $guarded =[];
}
