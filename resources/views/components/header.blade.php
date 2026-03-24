@props(['title','description'])

<div class="bg-orange-900 px-6 py-24 sm:py-32 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-5xl font-semibold tracking-tight text-orange-200 sm:text-7xl">{{$title}}</h2>
        <p class="mt-8 text-lg font-medium text-pretty text-orange-600 sm:text-xl/8">{{$description}}</p>
    </div>
</div>
