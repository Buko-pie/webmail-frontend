@extends('layouts.body')

@section('content')
<div >
  @include('navbars.navbar_1')
  <sidebar-component ref="sidebarComponent"
    :gmail_user="{{ json_encode(LaravelGmail::user())}}"
    :url_base="{{ json_encode($base) }}"
    {{-- :csrf_token="{{ json_encode(csrf_token()) }}" --}}
  />
</div>
@endsection