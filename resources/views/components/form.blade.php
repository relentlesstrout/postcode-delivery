<form action="{{ route('shop.can-deliver') }}" method="POST" class="relative mx-auto flex max-w-md items-stretch">
    @csrf
    <label for="postcode" class="mt-10 absolute -top-2.5 left-2.5 inline-block rounded bg-orange-50 px-1 text-xs font-medium text-orange-700">
        Postcode
    </label>
    <input
        id="postcode"
        type="text"
        name="postcode"
        placeholder="NE21 5JK"
        class="px-4 flex-1 rounded-l-md border border-r-0 border-orange-200 bg-orange-50 text-sm text-orange-950 placeholder:text-orange-300 outline-none focus:border-orange-400 transition-colors"
    />

    <button
        type="submit"
        class="rounded-r-md bg-orange-700 px-4 py-2 text-sm font-medium text-orange-50 transition-colors hover:bg-orange-800 active:bg-orange-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-700"
    >
        Check delivery
    </button>
</form>
