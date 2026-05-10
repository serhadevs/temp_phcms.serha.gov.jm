<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApiClient extends Model implements Auditable
{
     use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name', 'client_id', 'client_secret', 'is_active'];
}