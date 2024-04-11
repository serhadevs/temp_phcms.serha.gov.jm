<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prices extends Model
{
    use HasFactory;

    protected $table = 'prices';

    protected $fillable = [
        'id',
        'application_type_id',
        'price',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function applicationType():BelongsTo{
        return $this->belongsTo(ApplicationType::class, 'application_type_id', 'id');
    }
}
