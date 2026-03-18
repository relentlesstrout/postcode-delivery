<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="en-GB">

<head>
    <meta charset="UTF-8" />
    @vite('resources/css/app.css')
    <title>Postcode Delivery</title>
</head>

<body class="h-full">
<div class="min-h-full bg-orange-50">
    <div class="py-10">
        <main>
            <div class="bg-orange-900 px-6 py-24 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-5xl font-semibold tracking-tight text-orange-200 sm:text-7xl">Postcode Delivery</h2>
                    <p class="mt-8 text-lg font-medium text-pretty text-orange-600 sm:text-xl/8">Input your postcode to find all shops delivering to your area.</p>
                </div>
            </div>
            <div class="mx-auto max-w-4xl px-6 py-12 lg:px-8">
                <div class="mt-10 sm:ml-16 sm:mt-0 sm:flex-none">
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
                            class="flex-1 rounded-l-md border border-r-0 border-orange-200 bg-orange-50 px-3 py-2 text-sm text-orange-950 placeholder:text-orange-300 outline-none focus:border-orange-400 transition-colors"
                        />
                        <button
                            type="submit"
                            class="rounded-r-md bg-orange-700 px-4 py-2 text-sm font-medium text-orange-50 transition-colors hover:bg-orange-800 active:bg-orange-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-700"
                        >
                            Check delivery
                        </button>
                    </form>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <a href="{{route('shop.create')}}">
                        <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Add Store
                        </button>
                    </a>
                </div>
            </div>
    </div>
            </div>
        </main>
    </div>
</div>


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@stack('scripts')
</body>

</html>
