<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TouristEstManagers extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "tourist_establishment_managers";

    protected $fillable = [
        'id',
        'tourist_establishment_id',
        'firstname',
        'lastname',
        'post_held',
        'qualifications',
        'nationality',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('system_operation_type_id', 10)
            ->where('application_type_id', 6);
    }
}
