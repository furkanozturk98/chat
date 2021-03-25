<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

    <script src="{{ asset('js/app.js') }}" defer></script>

    @auth
        <script>
            window.api_token = '{{ auth()->user()->api_token  }}'
        </script>
    @endauth

</head>
<body>
<div id="app">
       <chat-index :current-user="{{ auth()->user() }}"></chat-index>
</div>
</body>
</html>
