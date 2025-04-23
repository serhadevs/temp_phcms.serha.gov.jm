<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'answers';
    public $timestamps = true;
    protected $guarded = [];
}
