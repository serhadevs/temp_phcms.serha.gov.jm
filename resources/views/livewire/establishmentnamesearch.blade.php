<div wire:init>
    <div class="position-relative">
        <input 
            type="text" 
            wire:model.debounce.500ms="establishmentName"
            class="form-control" 
            placeholder="Search Establishment Name..."
        >
        
        <div wire:loading wire:target="establishmentName" class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @if(!empty($establishmentNames) && $establishmentNames->count() > 0)
        <ul class="list-group mt-2">
            @foreach($establishmentNames as $establishment)
                <li class="list-group-item">
                    {{ $establishment->establishment_name }}
                </li>
            @endforeach
        </ul>
    @endif
</div>