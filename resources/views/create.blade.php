<x-layout
    header-title="Postcode Delivery"
    header-description="Input your postcode to find all shops delivering to your area."
>
    <div class="min-h-screen bg-gray-50 py-12 px-4">
        <div class="max-w-2xl mx-auto">

            <div class="mb-8">
                <h2 class="text-4xl font-bold text-orange-900 tracking-tight">Add a new store</h2>
                <p class="mt-1 text-sm text-orange-500">Fill in the details below to register a new store.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-8">
                <form action="{{ route('shop.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label for="name" class="block text-sm font-medium text-orange-900 mb-1">Store Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="e.g. The Corner Shop"
                                value="{{ old('name') }}"
                                class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm text-orange-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            @error('name')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3 pb-2.5">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="hidden" name="is_open" value="0">
                                    <input type="checkbox" name="is_open" value="1" class="sr-only peer"
                                        {{ old('is_open') ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-orange-200 rounded-full peer-checked:bg-orange-500 transition-colors duration-200"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                                </div>
                                <span class="text-sm font-medium text-orange-900 whitespace-nowrap">Currently Open</span>
                            </label>
                            @error('is_open')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-orange-900 mb-1">Latitude</label>
                            <input
                                type="number"
                                name="latitude"
                                id="latitude"
                                step="any"
                                min="-90"
                                max="90"
                                value="{{ old('latitude') }}"
                                placeholder="e.g. 51.5074"
                                class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            @error('latitude')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-orange-900 mb-1">Longitude</label>
                            <input
                                type="number"
                                name="longitude"
                                id="longitude"
                                min="-180"
                                max="180"
                                step="any"
                                value="{{ old('longitude') }}"
                                placeholder="e.g. -0.1278"
                                class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            @error('longitude')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="max_delivery_distance" class="block text-sm font-medium text-orange-900 mb-1">Max Delivery Distance (km)</label>
                            <input
                                type="number"
                                name="max_delivery_distance"
                                id="max_delivery_distance"
                                min="1"
                                value="{{ old('max_delivery_distance') }}"
                                placeholder="e.g. 10"
                                class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            />
                            @error('max_delivery_distance')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-orange-900 mb-1">Type of Store</label>
                            <select
                                name="type"
                                id="type"
                                class="w-full rounded-lg border border-orange-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition bg-white"
                            >
                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select a type...</option>
                                <option value="restaurant" {{ old('type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                                <option value="takeaway" {{ old('type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                                <option value="shop" {{ old('type') == 'shop' ? 'selected' : '' }}>Shop</option>
                            </select>
                            @error('type')
                            <p class="text-red-500 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-orange-100"></div>

                    <div class="flex justify-between items-center">
                        <a href="{{route('shop.index')}}" class="text-sm text-orange-500 hover:underline font-semibold">Back to shops</a>
                        <button
                            type="submit"
                            class="bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                        >
                            Add Store
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>

