<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        <div class="text-gray-500 text-xs">
            @php
              $field = $getName();
              if(!empty($getRecord()[$field])){
               echo $getRecord()[$field]->diffForHumans();
              }
            @endphp
        </div>
    </div>
</x-dynamic-component>
