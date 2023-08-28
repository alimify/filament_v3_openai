<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        <div class="h-36 w-full">
            <img x-bind:src="'/storage/'+state"/>
            <a x-bind:href="'/storage/'+state" target="_blank" 
                                               class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600">
                Download
            </a>
        </div>
    </div>
</x-dynamic-component>
