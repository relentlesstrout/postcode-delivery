<x-layout
    header-title="Postcode Delivery"
    header-description="Input your postcode to find all shops delivering to your area."
>
    <div class="mx-10 max-w-full px-6 py-12">
        <div class="flex justify-between items-center mb-8">
            <x-form/>
            <x-button/>
        </div>
        <div class="mb-6">
            @error('postcode')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <x-table :stores="$stores"/>
    </div>
</x-layout>
