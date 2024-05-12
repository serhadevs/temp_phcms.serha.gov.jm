<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditTransactionsChangedColumns extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "edit_transaction_changed_columns";

    protected $fillable = [
        'id',
        'edit_transaction_id',
        'column_name',
        'old_value',
        'new_value',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}
