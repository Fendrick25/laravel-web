@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

    {{-- Movie Carousel --}}
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="slide 3"></button>
        </div>

        <div class="carousel-inner">
            @foreach ($carousel as $movie)
                <div class="carousel-item {{ $loop->index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('/storage/images/movies/background/' . $movie->backgroundUrl) }}" class="d-block"
                         style="width:100%; height:500px">
                    <div class="carousel-caption text-white mb-lg-5 text-right">
                        <div class="row-sm-2 mb-3 d-flex">{{$movie->genres[0]->genre}} |
                            {{ date('Y', strtotime($movie->release_date)) }}
                        </div>
                        <div class="row-sm-2 mb-3 d-flex">
                            <h3>{{ $movie->title }}</h3>
                        </div>
                        <div class="row-sm-2 mb-lg-5 d-flex">
                            <p>{{ $movie->description }}</p>
                        </div>

                        <div class="row-sm-2 mb-lg-5 d-flex">
                            <form action="{{ route('watchlists.store', ['id' => $movie->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Add To Watchlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="content">
        <div class="logo-text text-white m-5 align-self-center">
            <p style="font-size: 175%">Popular</p>
        </div>

        {{-- Popular Movie --}}
        <div class="d-flex row justify-content-evenly gap-2 mt-3 px-5">
            @foreach ($movies as $movie)
                <div class="card bg-dark text-white border-2" style="width: 230px">
                    <a href={{'movies/' . $movie->id }} style="text-decoration:none;color:white">
                        <img src={{ asset('storage/images/movies/thumbnail/' . $movie->thumbnailUrl) }} alt=""
                             class="card-img-top mt-3" style="width:100%; height:300px">
                        <div class="card-body">
                            <div class="card-title">
                                <h5>{{ $movie->title }}</h5>
                            </div>
                            <div class="card-text">{{ date('Y', strtotime($movie->release_date))}}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Popular Movie Pagination Links --}}
        <div class="mx-lg-5 m-md-3">
            {{$movies->links()}}
        </div>

        {{-- Show + Search Bar --}}
        <div class="mt-3 d-flex justify-content-between">
            <div class="logo-text text-white d-flex m-5 align-self-center">
                <p class="text" style="font-size: 175%">Show</p>
            </div>
            <div class="mx-5 align-self-center">
                <form action={{ route('home') }}>
                    <div class="form-outline btn-secondary d-flex">
                        <input type="search" id="" class="form-control bg-dark text-white mx-3"
                               placeholder="Search movie" name="search" />
                    </div>
                </form>
            </div>
        </div>

        {{-- Genre --}}
        <div class="d-flex flex-row mt-1 mx-5 text-white">
            @foreach ($genres as $genre)
                <div class="p-2">
                    <a href="{{ route('home', ['genre' => $genre->id]) }}">
                        <button type="button" class="btn btn-secondary w-100">{{$genre->genre}}</button>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Sort by --}}
        <h6 class="text-white row mx-5 mt-3" style="font-size: 150%">Sort By</h6>
        <div class="d-flex flex-row mt-4 mx-5">
            <div class="p-2">
                <a href="{{ route('home', ['sort' => 'latest']) }}">
                    <button type="button" class="btn btn-secondary w-100">Latest</button>
                </a>
            </div>
            <div class="p-2">
                <a href="{{ route('home', ['sort' => 'asc']) }}">
                    <button type="button" class="btn btn-secondary w-100">A-Z</button>
                </a>
            </div>
            <div class="p-2">
                <a href="{{ route('home', ['sort' => 'desc']) }}">
                    <button type="button" class="btn btn-secondary w-100">Z-A</button>
                </a>
            </div>
        </div>

        {{-- Admin Add Movie --}}
        @if (Gate::allows('admin', Auth::user()))
            <div class="mt-4 mb-3 mx-5 d-flex justify-content-end">
                <a href="{{ route('movies.create') }}">
                    <button class="btn btn-danger">Add Movie</button>
                </a>
            </div>
        @endif

        {{-- More Movie --}}
        <div class="d-flex row justify-content-evenly gap-2 mt-3 px-5">
            @foreach ($sorted as $movie)
                <div class="card bg-dark text-white border-2" style="width: 230px">
                    <a href={{ 'movies/' . $movie->id }} style="text-decoration:none;color:white">
                        <img src={{ asset('storage/images/movies/thumbnail/' . $movie->thumbnailUrl) }} alt=""
                             class="card-img-top mt-3" style="width:100%; height:300px">
                        <div class="card-body">
                            <div class="card-title">
                                <h5>{{ $movie->title }}</h5>
                            </div>
                            <div class="card-text d-flex justify-content-between">
                                <div class="card-text">{{ date('Y', strtotime($movie->release_date))}}</div>

                                {{-- Add to Watchlist --}}
                                <div class="add" style="height: 25px; width: 15px">
                                    <form action="{{ route('watchlists.store', ['id' => $movie->id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="{{$movie->id}}">
                                        <button type="submit"
                                                style="border: none; background: none; color:white">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>


        {{-- More Movie Pagination Links --}}
        <div class="mx-lg-5 m-md-3">
            {{$sorted->links()}}
        </div>
    </div>

@endsection
