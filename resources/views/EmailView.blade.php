@extends('layouts.body')

@section('content')
<div id="EmailViewFull">
  <email-full-view :data="{{ json_encode($data) }}"/>
</div>
@endsection