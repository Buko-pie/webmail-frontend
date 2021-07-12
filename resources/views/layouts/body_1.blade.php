<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" id="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Webmail</title>

    <link href="{{ asset('css/syncfusion_material.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebarTemplate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inboxTemplate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/splitpanes/dist/splitpanes.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app">
        @include('navbars.navbar_1')
        <sidebar-component ref="sidebarComponent"
          :routes="{{ json_encode($routes) }}"
          :gmail_user="{{ json_encode(LaravelGmail::user())}}"
          {{-- :csrf_token="{{ json_encode(csrf_token()) }}" --}}
        />
        <div class="flex">
            <main class="w-full">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
