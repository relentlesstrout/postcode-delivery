@props(['label', 'route'])

<a href="{{ route($route) }}"
   class="inline-block rounded-md bg-orange-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-orange-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600"
>
    {{ $label }}
</a>
