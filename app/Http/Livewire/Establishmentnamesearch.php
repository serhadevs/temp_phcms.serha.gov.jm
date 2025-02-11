<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EstablishmentApplications;

class Establishmentnamesearch extends Component
{
    public $establishmentName = '';
    public $establishmentNames = [];

    protected $rules = [
        'establishmentName' => 'nullable|string'
    ];

    public function mount()
    {
        $this->establishmentNames = [];
    }
    
    public function updatedEstablishmentName()
    {
        if(strlen($this->establishmentName) > 2) {
            $this->establishmentNames = EstablishmentApplications::where('establishment_name', 'LIKE', '%' . $this->establishmentName . '%')
                ->limit(5)
                ->get();
        } else {
            $this->establishmentNames = [];
        }
    }
    
    public function render()
    {
        
        return view('livewire.establishmentnamesearch');
    }
}
