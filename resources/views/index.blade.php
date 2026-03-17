<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="en-GB">

<head>
    <meta charset="UTF-8" />
    <title>Postcode Delivery</title>
</head>

<body class="h-full">
<div class="min-h-full bg-gray-100">
    <div class="py-10">

        <main>
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <h1></h1>
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
