<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            @foreach ($chats as $chat)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a class="mt-2 font-medium text-sm text-gray-600 dark:text-gray-400" href="{{route('chat.join', $chat->id)}}">{{$chat->name}}</a>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</x-app-layout>