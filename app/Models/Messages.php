<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Messages extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'email_messages';

    protected $guarded = [];


    public function emailtypes(): HasOne
    {
        return $this->hasOne(EmailTypes::class, 'id', 'email_type_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }
}
