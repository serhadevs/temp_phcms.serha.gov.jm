<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TouristEstServices extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tourist_establishment_services';

    protected $fillable = [
        'id',
        'tourist_establishment_id',
        'name',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function editTransactions(): HasMany
    {
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
            ->where('application_type_id', 6)
            ->where('system_operation_type_id', 11);
    }
}
