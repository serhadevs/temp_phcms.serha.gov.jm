<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SMSMessages extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = "sms_templates";

    protected $fillable = [];

    
}
