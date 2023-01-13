@extends('layouts.app')

@section('title', 'Actors')

@section('content')
    <div class="px-4 mt-4">
        <h1 class="text-white">Actors</h1>
        <div class="d-flex justify-content-end">
            <div class="d-flex gap-3">
                <form action="{{ route('actors') }}" method="get" class="d-flex">
                    <input type="text" placeholder="Search Actor" name="q" class="form-control bg-dark text-white"
                        style="margin-right: 1rem">
                </form>

                {{-- Admin Add Actor --}}
                @if (Gate::allows('admin', Auth::user()))
                    <button class="btn btn-danger">
                        <a href="{{ route('actors.create') }}" class="text-white text-decoration-none">
                            Add Actor
                        </a>
                    </button>
                @endif

            </div>
        </div>

        <div class="d-flex row justify-content-evenly gap-4 mt-3">
            @foreach ($actors as $actor)
                <div class="card col-4 p-0 bg-dark" style="width: 18rem; border-color: white;">
                    <img src="{{ url('storage/images/actors/'. $actor->imageUrl) }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="text-white card-title">{{ $actor->name }}</h5>
                        @if (count($actor->characters))
                            <p class="text-white card-text">{{ $actor->characters[0]->movie->title }}</p>
                        @else
                            <p class="text-muted card-text">No movies</p>
                        @endif
                        <a href="{{ route('actors.show', ['id' => $actor->id]) }}"
                            class="text-white text-decoration-none btn btn-primary w-100">
                            Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection
