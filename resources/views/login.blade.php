@extends('layouts.body')

@section('content')
  <div id="app">
    <div id="login_form">
      <login-component :routes="{{ json_encode($routes) }}"/>
    </div>
  </div>
@endsection