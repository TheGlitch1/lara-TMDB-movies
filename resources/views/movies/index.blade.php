<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $viewType === 'trending' ? __('Trending') : __('All Movies') }}
            </h2>
            @if($viewType === 'trending')
            <div>
                <form action="{{ route('movies.trending') }}" method="GET">
                    <select name="period" onchange="this.form.submit()" class="rounded-md border-gray-300 py-2 bg-white text-md w-30">
                        <option value="day" {{ request('period', 'day') === 'day' ? 'selected' : '' }}>Day</option>
                        <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>Week</option>
                    </select>
                </form>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
            <div class="bg-red-500 text-white font-semibold rounded-lg border-l-4 border-red-700 p-4 mt-6" role="alert">
                <p class="font-bold">Error:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-8 px-4">
                    {{ $movies->links() }}
                </div>
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
                <div class="mt-8 px-4 mb-2">
                    {{ $movies->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>