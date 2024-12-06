<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditTransactions extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "edit_transactions";

    protected $fillable = [
        'id',
        'application_type_id',
        'table_id',
        'system_operation_type_id',
        'edit_type_id',
        'user_id',
        'facility_id',
        'reason',
        'created_at',
        'updated_at',
        'deleted_at',
        'approved'
    ];

    public $timestamps = true;

    public function changedColumns(): HasMany
    {
        return $this->hasMany(EditTransactionsChangedColumns::class, 'edit_transaction_id', 'id');
    }

    public function systemOperationType(): HasOne
    {
        return $this->hasOne(SystemOperationTypes::class, 'id', 'system_operation_type_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function editType(): HasOne
    {
        return $this->hasOne(EditTypes::class, 'id', 'edit_type_id');
    }

    public function establishmentClinic(): BelongsTo
    {
        return $this->belongsTo(EstablishmentClinics::class, 'table_id', 'id');
    }

    public function applicationType(): HasOne
    {
        return $this->hasOne(ApplicationType::class, 'id', 'application_type_id');
    }

    public function signOffs(): HasOne
    {
        return $this->hasOne(SignOff::class, 'id', 'table_id');
    }
}
