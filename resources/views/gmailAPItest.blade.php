<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1>{{ LaravelGmail::user() }}</h1>
  <h1>{{ LaravelGmail::check() }}</h1>
  @foreach (LaravelGmail::message()->take(2)->all( $pageToken = null ) as $item)
    {{-- <h3>{{ [$item] }}</h3> --}}
  @endforeach
  
  @if(LaravelGmail::check())
      <a href="{{ url('oauth/gmail/logout') }}">logout</a>
  @else
      <a href="{{ url('oauth/gmail') }}">login</a>
  @endif
</body>
</html>