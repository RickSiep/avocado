@extends('layout.master')

@section('content')
    <h2>{{$movie['properties']['title']}}</h2>
    <h3 class="mt-4">{{$movie['description']}}</h3>
@endsection
