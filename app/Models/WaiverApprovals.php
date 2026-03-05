<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaiverApprovals extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "waiver_approvals";
    protected $guarded =[];

    public function establishment(): BelongsTo{
        return $this->belongsTo(EstablishmentClinics::class, 'establishment_id', 'id');
    }
}
