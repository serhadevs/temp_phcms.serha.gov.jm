<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentCategories extends Model
{
    use HasFactory;

    protected $table = "establishment_categories";

    protected $fillable = [
        "id",
        "name",
        "created_at",
        "updated_at"
    ];

    public $timestamps = true;
}
