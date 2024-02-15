<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodEstablishment extends Model
{
    use HasFactory;

    protected $table = 'establishment_applications';

    protected $fillable = [

        'establishment_name',
        'establishment_address',
        'permit_no',
        'food_type',
        'telephone',
        'alt_telephone',
        'email',
        'trn',
        'zone',
        'establishment_category_id',
        'prev_est_closed',
        'current_est_closed',
        'closure_date',
        'sign_off_status',
        'reprint',
        'application_date',
        'user_id'
    ];

    public $timestamps = true;

    public function role()
    {
      return $this->belongsTo('App\Role');
    }

    public function facility()
    {
      return $this->belongsTo('App\Facility');
    }

    public function abbrName(){
        return $this->firstname[0].'. '.$this->lastname;
    }

    public static function facilityUsers(){
        $facility_users = User::where('facility_id', auth()->user()->facility_id)
                        ->get();

        return $facility_users;
    }
}
