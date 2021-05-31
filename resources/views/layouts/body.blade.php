<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/f8565b8867.js" crossorigin="anonymous"></script>
    <style>
        .pill{
            border-radius: 500px;
        }
        
        .shadow-black {
            --tw-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.25), 0 1px 2px 0 rgba(0, 0, 0, 0.15);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }
    </style>
</head>
<body>
    <div id="webmail">
        @include('navbars.navbar_1')
        <div class="flex">
            @include('navbars.sidebar_1')
            {{-- <main class="w-full">
                @yield('content')
            </main> --}}
        </div>
    </div>
</body>
</html>