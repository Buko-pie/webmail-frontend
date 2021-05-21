<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/f8565b8867.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
        @include('navbars.navbar_1')
        <sidebar-component ref="sidebarComponent" :routes="{{ json_encode($routes) }}"/>
        <div class="flex">
            <main class="w-full">
                @yield('content')
            </main>
        </div>
    </div>
    <link href="{{ asset('css/tablecell_override.css') }}" rel="stylesheet">
</body>
</html>

<script>
  
</script>