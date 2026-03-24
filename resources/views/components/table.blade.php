<div class="overflow-x-auto shadow-sm ring-1 ring-orange-200 rounded-lg">
    <table class="min-w-full divide-y divide-orange-200">
        <thead class="bg-orange-100">
        <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-orange-900 sm:pl-6">Name</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-orange-900">Latitude</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-orange-900">Longitude</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-orange-900">Max Delivery Distance</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-orange-900">Type</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-orange-900">Open?</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-orange-100 bg-white">
        @forelse ($stores as $store)
            <tr class="hover:bg-orange-50 transition-colors">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-orange-900 sm:pl-6">
                    {{ $store->name }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-orange-700">
                    {{ $store->latitude }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-orange-700">
                    {{ $store->longitude }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-orange-700">
                    {{ $store->max_delivery_distance }}km
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-orange-700">
                    {{ $store->type }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                            {{ $store->is_open ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $store->is_open ? 'Open' : 'Closed' }}
                        </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-10 text-center text-sm text-orange-400">
                    No stores found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
