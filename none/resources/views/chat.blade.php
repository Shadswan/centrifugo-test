<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('CHAT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a>{{$currChat->id}}. {{$currChat->name}}</a>
                    <button class="mt-2 font-medium text-sm text-gray-600 dark:text-gray-400" id="getToken">Сосал?</button>
                    <a href="{{route('canals')}}">Каналы</a>
                    <form id="chatForm" method="POST" action="{{route('send.message')}}" >
                        @csrf
                        <input name="message" type="text" required>
                        <input type="submit" value="send">
                    </form>
                    <!--Центрифугу подключаем-->
                    <script src="https://unpkg.com/centrifuge@5.0.1/dist/centrifuge.js"></script>
                    <!--Ajax подключаем-->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script>
                        //получаем токен по кнопке
                        document.getElementById('getToken').addEventListener('click', function() {
                            fetch('/api/token')
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('ахвахывахыв лошара');
                                    }
                                    return response.json();
                                }).then(data => {
                                    token = data.token;
                                    console.log('Токен:', token);
                                    //подлючаемся
                                    const centrifuge = new Centrifuge("ws://localhost:8000/connection/websocket", {
                                        token: token
                                    });
                                    centrifuge.on('connecting', function(ctx) {
                                        console.log(`connecting: ${ctx.code}, ${ctx.reason}`);
                                    }).on('connected', function(ctx) {
                                        console.log(`connected over ${ctx.transport}`);
                                    }).on('disconnected', function(ctx) {
                                        console.log(`disconnected: ${ctx.code}, ${ctx.reason}`);
                                    }).connect();

                                    const sub = centrifuge.newSubscription("channel");

                                    sub.on('publication', function(ctx) {
                                        container.innerHTML = ctx.data.value;
                                        document.title = ctx.data.value;
                                    }).on('subscribing', function(ctx) {
                                        console.log(`subscribing: ${ctx.code}, ${ctx.reason}`);
                                    }).on('subscribed', function(ctx) {
                                        console.log('subscribed', ctx);
                                    }).on('unsubscribed', function(ctx) {
                                        console.log(`unsubscribed: ${ctx.code}, ${ctx.reason}`);
                                    }).subscribe();

                                    $(document).ready(function(){
                                        $('#chatForm').on('submit', function(e){
                                            e.preventDefault();
                                            $.ajax({
                                                url: "{{route('send.message')}}",
                                                method: 'POST',
                                                data: $(this).serialize(),
                                                success: function(response){
                                                    $('#response').text(response.message);
                                                },
                                                error: function(xhr){
                                                    console.error(xhr.responseText);                                                            
                                                }
                                            });
                                        });
                                    });
                                    //ошибки
                                }).catch(error => {
                                    console.error('Сосал?', error);
                                });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>