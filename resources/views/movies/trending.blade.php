<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trending') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="container py-5 px-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($movies as $movie)
                        <div class="card rounded overflow-hidden shadow-lg max-w-xs">
                            <div class="relative overflow-hidden"> <!-- This maintains an aspect ratio -->
                                <img class="w-full h-full object-cover" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}" alt="{{ $movie->title }}">
                            </div>
                            <div class="px-4 py-2">
                            <div class="font-bold text-xl mb-2 truncate" title="{{ $movie->title }}">{{ $movie->title }}</div>
                                <p class="text-gray-600 text-sm">
                                    {{ Str::limit($movie->overview, 100) }}
                                </p>
                            </div>
                            <div class="px-4 py-2">
                                <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-medium text-gray-700 mr-2 mb-2">
                                    {{ $movie->vote_average ?? 'No rating' }} / 10
                                </span>
                                <a href="{{ route('movie.details', $movie->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1 px-2 rounded">
                                    Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>