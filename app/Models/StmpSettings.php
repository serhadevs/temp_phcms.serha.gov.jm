<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StmpSettings extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'stmp_settings';

    public $fillable = [
        'id',
        'host',
        'port',
        'username',
        'password',
        'encrytion',
        'from_address'

    ];
    
    public $timestamps  = true;


}
