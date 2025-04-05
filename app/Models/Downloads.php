<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Downloads extends Model implements Auditable
{
    use HasFactory;
    // use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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
