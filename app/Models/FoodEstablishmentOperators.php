<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class FoodEstablishmentOperators extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    // use Loggable;
    use \OwenIt\Auditing\Auditable;

    protected $table = "food_est_operators";

    protected $fillable = [
        'id',
        'establishment_application_id',
        'name_of_operator',
        'created_at',
        'updated_at',
        // Needs to be added to live table
        'deleted_at'
    ];

    public function foodEstablishment(): BelongsTo
    {
        return $this->belongsTo(EstablishmentApplications::class, 'establishment_application_id', 'id');
    }

    public function editTransactions():HasMany{
        return $this->hasMany(EditTransactions::class, 'table_id', 'id')
        ->where('application_type_id', 3)
        ->where('system_operation_type_id', 9);
    }

    public $timestamps = true;
}
