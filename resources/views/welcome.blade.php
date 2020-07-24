<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Happy book</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
        <link href="/public/css/app.css" rel="stylesheet">

    </head>
    <body>
        <div id="app">
            <div class="flex-center position-ref full-height">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ route('logout') }}">Выход</a>
                        @else
                            <a href="{{ route('login') }}">вход админа</a>
                        @endauth
                    </div>
                @endif

                <div class="container mt-3">
                    <div class="content">
                        <router-link to="/">Cписок книг</router-link>
                        <hr>
                        <router-view></router-view>
                    </div>
                </div>

            </div>
        </div>
        <script>
            window.api_token = '<?=Auth::user() ? Auth::user()->api_token : ''?>';
        </script>
    <script src="/public/js/app.js"></script>

    </body>
</html>
