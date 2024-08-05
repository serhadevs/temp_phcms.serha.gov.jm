<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTokens extends Model
{
    use HasFactory;

    public $table = 'personal_access_tokens';
    protected $guard = [];

}
