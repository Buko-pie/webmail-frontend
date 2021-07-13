@extends('layouts.body')

@section('content')
<div >
  @include('navbars.navbar_1')
  <sidebar-component ref="sidebarComponent"
    :routes="{{ json_encode($routes) }}"
    :gmail_user="{{ json_encode(LaravelGmail::user())}}"
    {{-- :csrf_token="{{ json_encode(csrf_token()) }}" --}}
  />
</div>
@endsection