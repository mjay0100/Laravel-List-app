<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            NoteBook
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 capitalize">
            <x-link-button href="{{ route('notebooks.create') }}">+ New NoteBook</x-link-button>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            @forelse ($notebooks as $note)
                <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                    <h2 class="font-bold text-2xl text-indigo-600">
                        <a class="hover:underline">
                            {{ $note->name }}

                        </a>
                    </h2>

                </div>
            @empty
                <p>You have no notes</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
