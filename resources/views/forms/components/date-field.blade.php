<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        <div class="text-gray-500 text-xs">
            @php
              $field = $getName();
              if($getRecord()->$field){
               echo $getRecord()->$field->diffForHumans();
              }elseif($field == "updatet_at"){
                echo $getRecord()->updated_at->diffForHumans();
              }
            @endphp
        </div>
    </div>
</x-dynamic-component>
