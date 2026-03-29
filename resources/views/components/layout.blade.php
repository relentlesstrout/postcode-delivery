<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="en-GB">

    <head>
        <meta charset="UTF-8" />
        @vite('resources/css/app.css')
        <title>{{ $title ?? 'Postcode Delivery' }}</title>
    </head>

    <body class="h-full">
    <div class="min-h-full bg-orange-50">
        <div class="py-10">
            <main>
                <x-header
                    :title="$headerTitle ?? 'Postcode Delivery'"
                    :description="$headerDescription ?? ''"
                />
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
    </body>

</html>
