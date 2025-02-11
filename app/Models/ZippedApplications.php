<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class ZippedApplications extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'zipped_applications';

    protected $fillable = [
        'id',
        'application_type_id',
        'application_id',
        'download_id',
        'created_at',
        'updated_at',
        'written',
        'deleted_at'
    ];

    public $timestamps = true;

    public function payment():HasMany{
        return $this->hasMany(Payments::class, 'application_id', 'application_id');
    }

    public function download():BelongsTo{
        return $this->belongsTo(Downloads::class, 'download_id', 'id')->withTrashed();
    }

    public function permitApplication():HasOne{
        return $this->hasOne(PermitApplication::class, 'id', 'application_id')->withTrashed();
    }

    public function establishmentApplication():HasOne{
        return $this->hasOne(EstablishmentApplications::class, 'id', 'application_id')->withTrashed();
    }
}
