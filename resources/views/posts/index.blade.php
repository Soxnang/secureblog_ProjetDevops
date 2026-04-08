<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Blog — Articles
            </h2>
            @auth
            <a href="{{ route('posts.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Nouvel article
            </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700
                        px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @forelse($posts as $post)
            <div class="bg-white shadow rounded-lg p-6 mb-4">
                <h3 class="text-xl font-bold mb-2">
                    <a href="{{ route('posts.show', $post) }}"
                       class="text-blue-600 hover:underline">
                        {{ $post->title }}
                    </a>
                </h3>
                <p class="text-gray-500 text-sm mb-3">
                    Par {{ $post->user->name }}
                    · {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="text-gray-700">
                    {{ Str::limit($post->content, 150) }}
                </p>
            </div>
            @empty
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                Aucun article publié pour le moment.
            </div>
            @endforelse

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
