<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use OwenIt\Auditing\Contracts\Auditable;

class SignOff extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    // use Loggable;

    protected $table = "sign_offs";

    protected $fillable = [
        'id',
        'is_granted',
        'permit_no',
        'ecard_id',
        'sign_off_date',
        'expiry_date',
        'user_id',
        'application_type_id',
        'application_id',
    ];

     protected $dates = [
        'sign_off_date',
        'expiry_date',
    ];

    public $timestamps = true;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function application_type(): HasOne
    {
        return $this->hasOne(ApplicationType::class, 'id', 'application_type_id');
    }

    public function permitApplication(): HasOne
    {
        return $this->hasOne(PermitApplication::class, 'id', 'application_id');
    }

    public function establishmentApplication($value): HasOne
    {
        // dd($value);
        return $this->hasOne(EstablishmentApplications::class, 'id', 'application_id');
    }

    public function estApplication(): HasOne
    {
        return $this->hasOne(EstablishmentApplications::class, 'id', 'application_id');
    }

    public function healthCertApplication(): HasOne
    {
        return $this->hasOne(HealthCertApplications::class, 'id', 'application_id');
    }

    public function swimmingPool(): HasOne
    {
        return $this->hasOne(SwimmingPoolsApplications::class, 'id', 'application_id');
    }

    public function touristEstApplication(): HasOne
    {
        return $this->hasOne(TouristEstablishments::class, 'id', 'application_id');
    }

    public function application($query)
    {
        return $query->when($this->application_type_id == '1', function ($q) {
            return $q->permitApplication;
        });
    }

    // Relationship to access history
    public function accessHistory()
    {
        return $this->hasMany(EcardAccessHistory::class);
    }

    // Track a new access
    public function trackAccess($accessType, $accessMethod, $request)
    {
        return EcardAccessHistory::create([
            'sign_off_id' => $this->id,
            'ecard_id' => $this->ecard_id,
            'access_type' => $accessType, // 'viewed' or 'downloaded'
            'access_method' => $accessMethod,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accessed_at' => now()
        ]);
    }

    // Get first access
    public function getFirstAccess()
    {
        return $this->accessHistory()
            ->where('access_type', 'viewed')
            ->orderBy('accessed_at')
            ->first();
    }

    // Get all downloads
    public function getDownloads()
    {
        return $this->accessHistory()
            ->where('access_type', 'downloaded')
            ->orderBy('accessed_at', 'desc')
            ->get();
    }

    // Get download count
    public function getDownloadCount()
    {
        return $this->accessHistory()
            ->where('access_type', 'downloaded')
            ->count();
    }

    // Get card status
    public function getCardStatus()
    {
        if ($this->getDownloadCount() > 0) {
            return 'downloaded';
        } elseif ($this->getFirstAccess()) {
            return 'accessed';
        } else {
            return 'issued';
        }
    }
}
