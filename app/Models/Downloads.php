<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downloads extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'downloads';

    protected $fillable = [
        'id',
        'application_type_id',
        'application_amount',
        'category',
        'download_url',
        'download_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function zippedApplications(): HasMany
    {
        return $this->hasMany(ZippedApplications::class, 'download_id', 'id');
    }

    public $timestamps = true;
}
