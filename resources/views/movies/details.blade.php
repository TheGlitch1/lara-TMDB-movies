<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $movie->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container py-5">
                    <div class="flex flex-wrap md:flex-nowrap space-y-4 md:space-y-0 md:space-x-6">
                        <div class="flex-none">
                            <img src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}" alt="{{ $movie->title }}" class="w-64 md:w-96 rounded-lg shadow-md">
                        </div>
                        <div class="flex-grow space-y-5">
                            <h2 class="text-2xl font-bold">{{ $movie->title }}</h2>
                            <p class="text-gray-500">{{ $movie->release_date }}</p>
                            <div class="space-y-3">
                                <p>{{ $movie->overview }}</p>
                                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                    Rating: {{ $movie->vote_average ?? 'No rating' }} / 10
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
