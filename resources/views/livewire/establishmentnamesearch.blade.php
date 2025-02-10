<div>
    <input type="text" wire:model.live="establishmentName" class="form-control"
        placeholder="Search Establishment Name...">

    @if (count($establishmentNames) > 0)
        <ul class="list-group">
            @foreach ($establishmentNames as $establishmentName)
                <li class="list-group-item list-group-item-action"
                    wire:click="selectEstablishmentName('{{ $establishmentName->name }}')">{{ $establishmentName->name }}
                </li>
            @endforeach
    @endif
</div>
