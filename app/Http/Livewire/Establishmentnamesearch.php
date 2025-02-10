<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EstablishmentApplications;

class Establishmentnamesearch extends Component
{
    public $establishmentName = '';
    public $establishmentNames = [];

    public function render()
    {
        if($this->establishmentName == ''){
            $this->establishmentNames = [];
        }else{
            $this->establishmentNames = EstablishmentApplications::search($this->establishmentName)->get(); 
            dd($this->establishmentNames);
          
        }
        return view('livewire.establishmentnamesearch');
    }
}
