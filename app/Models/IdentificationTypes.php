<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentificationTypes extends Model
{
    use HasFactory;

    public $table = 'identification_types';
    protected $fillable = [
        'name',
        'abbr',
    ];

    public function collectedCards()
    {
        return $this->belongsTo(CollectedCards::class, 'identification_type_id');
    }
}
