<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $movie->title }} 
            {{ $movie->id }} 
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row">

                    <!-- Image Section -->
                    <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                        <img src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}" alt="{{ $movie->title }}" class="rounded-lg w-full shadow-lg mb-6 md:mb-0">
                    </div>

                    <!-- Details Section -->
                    <div class="md:w-2/3 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-3xl font-semibold text-gray-800">{{ $movie->title }}</h1>
                                @if($movie->tagline)
                                    <p class="text-gray-500 italic">{{ $movie->tagline }}</p>
                                @endif
                                @if($movie->original_title !== $movie->title)
                                    <p class="text-gray-500 italic">{{ $movie->original_title }}</p>
                                @endif
                            </div>
                            <span class="inline-block bg-blue-200 text-blue-800 rounded-full px-3 py-1 text-lg font-semibold">
                                {{ $movie->vote_average ?? 'No rating' }} / 10
                            </span>
                        </div>

                        <!-- More Details -->
                        <div class="flex space-x-4">
                            <p class="text-gray-600">{{ $movie->release_date }}</p>
                            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                                Language: {{ strtoupper($movie->original_language) }}
                            </span>
                            <span class="inline-block bg-{{ $movie->adult ? 'red' : 'green' }}-200 text-{{ $movie->adult ? 'red' : 'green' }}-800 rounded-full px-3 py-1 text-sm font-semibold">
                                {{ $movie->adult ? 'Adult' : 'Family Friendly' }}
                            </span>
                        </div>

                        <div class="border-t border-gray-200 py-4">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Overview</h2>
                            <p class="text-gray-600">{{ $movie->overview }}</p>
                        </div>

                        <!-- Optional Homepage Button -->
                        @if($movie->homepage)
                            <a href="{{ $movie->homepage }}" target="_blank" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Official Homepage
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
