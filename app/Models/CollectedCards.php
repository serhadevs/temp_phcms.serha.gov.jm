<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CollectedCards extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = "collected_cards";
    protected $guarded = [];

    public $timestamps = true;

    public function identificationType()
    {
        return $this->belongsTo(IdentificationTypes::class, 'identification_type_id', 'id');
    }

    public function permit_application()
    {
        return $this->belongsTo(PermitApplication::class, 'app_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
