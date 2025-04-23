<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use \Illuminate\Database\Eloquent\SoftDeletes;  


class Coupons extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    
    protected $table = "coupons";
    public $timestamps = true;
      
    protected $guarded = [];
}
