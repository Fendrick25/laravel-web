@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

    <div class="banner bg-dark text-white">
        <img src={{ asset('/storage/images/movies/background/' . $movie->backgroundUrl) }}
            style="height:500px;width:100%;opacity:0.5">

        <div class="content d-flex justify-content-evenly mx-5 mt-5">
            <div class="col">
                <div class="row m-2">
                    <h2>{{ $movie->title }}</h2>
                </div>

                <div class="row m-4">
                    <div class="col-sm-2">Genre</div>
                    @foreach ($movie->genres as $genre)
                        <div class="col-sm-2">
                            <span class="border rounded p-2">{{$genre->genre}}</span>
                        </div>
                    @endforeach
                </div>

                <div class="row m-4">
                    <div class="col-sm-2">Release Year</div>
                    <div class="col">{{ date('d F Y', strtotime($movie->release_date))}}</div>
                </div>

                <div class="row m-4">
                    <div class="col-sm-2">Storyline</div>
                    <div class="col">{{$movie->description}}</div>
                </div>

                <div class="row m-4">
                    <div class="col-sm-2">Director</div>
                    <div class="col">{{$movie->director}}</div>
                </div>

                @if (Gate::allows('admin', Auth::user()))
                    <div class="row m-4">
                        <div class="col-1">
                            <a href="{{ route('movies.edit', ['id' => $movie->id]) }}">
                                <button class="btn btn-secondary w-100">Edit</button>
                            </a>
                        </div>
                        <div class="col-1">
                            <form action="{{ route('movies.destroy', ['id' => $movie->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <input type="hidden" name="id" value="{{$movie->id}}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actor --}}
    <div class="mt-4 text-white">
        <div class="row mb-2 mx-5">
            <h4>Actor</h4>
        </div>

        <div class="d-flex row justify-content-evenly gap-2 mt-3">
            @foreach ($characters as $character)
                <div class="col-2 mx-5 mt-4">
                    <div class="card bg-dark text-white border-2" style="width: 200px;">
                        <img src="{{ asset('/storage/images/actors/' . $character->actor->imageUrl) }}"
                             class="card-img-top" style="height: 250px">
                        <div class="card-body bg-dark" style="height: 110px">
                            <div class="actor-name card-title">{{$character->actor->name}}</div>
                            <div class="actor-character d-flex justify-content-between">{{$character->name}}</div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- More Movies --}}
            <div class="mt-4 text-white">
                <div class="row mx-5 mb-2">
                    <h4>More</h4>
                </div>

                <div class="d-flex row justify-content-evenly gap-2 mt-3 px-5">
                    @foreach ($movies as $movie)
                        <div class="card bg-dark text-white border-2" style="width: 230px">
                            <img src={{ asset('/storage/images/movies/thumbnail/' . $movie->thumbnailUrl) }}
                             class="card-img-top mt-3" style="width:100%; height:300px">
                            <div class="card-body">
                                <div class="card-title">{{$movie->title}}</div>
                                <div class="card-text d-flex justify-content-between">
                                    <div class="card-text">{{ date('Y', strtotime($movie->release_date))}}</div>
                                    <div style="height: 25px; width: 15px">
                                        <form action="{{ route('watchlists.store', ['id' => $movie->id]) }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="id" id="{{$movie->id}}">
                                            <button type="submit"
                                                    style="border:none;background: none; color:white">+</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

@endsection
