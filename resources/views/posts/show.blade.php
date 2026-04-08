<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            @can('update', $post)
            <div class="flex gap-2">
                <a href="{{ route('posts.edit', $post) }}"
                   class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                    Modifier
                </a>
                <form method="POST"
                      action="{{ route('posts.destroy', $post) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Supprimer cet article ?')"
                            class="bg-red-500 text-white px-4 py-2
                                   rounded hover:bg-red-600">
                        Supprimer
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-500 text-sm mb-6">
                    Par <strong>{{ $post->user->name }}</strong>
                    · {{ $post->created_at->format('d/m/Y') }}
                </p>
                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
