<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yungts97\LaravelUserActivityLog\Traits\Loggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
     use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'email',
        'password',
        'status',
        'firstname',
        'lastname',
        'role_id',
        'facility_id',
        'telephone',
        'last_seen',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
        'last_seen',
        'default_filter_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string>
     */
    protected $dates = ['deleted_at'];

    /**
     * Get facility users including soft deleted users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function facilityUsers()
    {
        return User::withTrashed()
            ->where('facility_id', Auth::user()->facility_id)
            ->get();
    }
    public static function facilityUserId()
    {
        // Ensure the user is authenticated to avoid potential errors
        if (auth()->check()) {
            return User::where('facility_id', auth()->user()->facility_id)->get();
        }
    
        // Return an empty collection or null if no authenticated user is found
        return collect();
    }

    public function facility(): HasOne
    {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }

    public function examSite(): HasOne{
        return $this->hasOne(ExamSites::class,'facility_id','facility_id');
    }

    public $timestamps = true;
}
