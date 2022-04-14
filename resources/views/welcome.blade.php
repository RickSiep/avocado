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
            <td>
                <a href="{{route('movie', $item['uid'])}}">{{$item['properties']['title']}}</a>
            </td>
            <td>{{$item['description']}}</td>
            <td>{{$item['properties']['release_date']}}</td>
            <td>
                @if($favorite->where('movie_id', $item['uid'])->count() == 0)
                <form action="{{route('favorite', $item['uid'])}}" method="POST">
                    @csrf
                    <button class="btn btn-success">Favorite</button>
                </form>
                @else
                    <form action="{{route('delete', $item['uid'])}}" method="POST">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger">Unfavorite</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
