@extends('layouts.app')

@section('content')
<div class="container">
    @if($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-thumbnail mb-3">
    @endif
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->description }}</p>
    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>
@endsection