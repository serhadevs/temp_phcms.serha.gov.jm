<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermitCategory extends Model
{
    use HasFactory;

    protected $table = 'permit_categories';
    protected $fillable = [
        "id",
        "name",
        "created_at",
        "updated"
    ];

    public function applications():BelongsTo{
        return $this->belongsTo(PermitApplication::class, 'id', 'permit_category_id');
    }

    public $timestamps = true;
}
