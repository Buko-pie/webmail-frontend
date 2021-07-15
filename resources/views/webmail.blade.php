@extends('layouts.body')

@section('content')
<div >
  @include('navbars.navbar_1')
  <p>{{ $base }}</p>
  <sidebar-component ref="sidebarComponent"
    :routes="{{ json_encode($routes) }}"
    :gmail_user="{{ json_encode(LaravelGmail::user())}}"
    :base="{{ json_encode($base) }}"
    {{-- :csrf_token="{{ json_encode(csrf_token()) }}" --}}
  />
</div>
@endsection