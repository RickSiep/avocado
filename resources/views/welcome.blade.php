@extends('layout.master')

@section('content')
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Release date</th>
        <th scope="col">Favorite</th>
    </tr>
    </thead>
    <tbody>
    @foreach($collection as $item)
        <tr>
            <td>{{$item['uid']}}</td>
            <td>{{$item['properties']['title']}}</td>
            <td>{{$item['description']}}</td>
            <td>{{$item['properties']['release_date']}}</td>
            <td><button class="btn btn-danger">Favorite</button></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
