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
            <x-header title="Postcode Delivery"
            description="Input your postcode to find all shops delivering to your area."
            />
            <div class="mx-10 max-w-full px-6 py-12">
                <div class="flex justify-between items-center mb-8">
                    <x-form/>
                    <x-button/>
                </div>
                <div class="mb-6">
                    @error('postcode')
                        <p class="text-red-500 text-sm">{{$message}}</p>
                    @enderror
                </div>
                <x-table :stores="$stores"/>
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>

</html>
