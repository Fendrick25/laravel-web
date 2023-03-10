@extends('layouts.app')

@section('title', 'Edit Movie')

@section('content')
    <div class="container bg-dark mt-2 rounded-2">
        <br>
        <h2 class="text-white">Add Movie</h2>
        <form method="POST" action="{{ route('movies.update', ['id' => $movie->id]) }}" class="inline-block"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{$movie->id}}">

            <label for="title" class="form-label text-white">Title</label>
            <input type="text" name="title" id="title" class="form-control bg-dark text-white"
                value="{{ $movie->title }}">

            <label for="description" class="form-label text-white mt-3">Description</label>
            <textarea type="text" name="description" id="description" class="form-control bg-dark text-white">{{ $movie->description }}</textarea>

            <p class="text-white mt-3 mb-1">Genres</p>

            <div>
                @foreach ($genre_types as $genre_type)
                    <div class="form-check-inline">
                        @if (in_array($genre_type->genre, $genres))
                            <input class="form-check-input" type="checkbox" value="{{$genre_type->id}}" name="genres[]"
                                checked>
                        @else
                            <input class="form-check-input" type="checkbox" value="{{$genre_type->id}}" name="genres[]">
                        @endif
                        <label class="form-check-label text-white"> {{$genre_type->genre}} </label>
                    </div>
                @endforeach
            </div>

            {{-- Actors --}}
            <p class="text-white mt-3 mb-1">Actors</p>
            <div class="margin-left: 1rem">
                <table class="table table-borderless" id="characters">
                    @foreach ($movie->characters as $character)
                        <tr>
                            <td>
                                <label class="form-label text-white">Actor Name</label>
                                <select name="actors[{{ $loop->index }}][id]" class="form-select bg-dark text-white">
                                    @foreach ($actors as $actor)
                                        @if ($actor->name === $character->actor->name)
                                            <option selected value="{{ $actor->id }}">{{ $actor->name }}</option>
                                        @else
                                            <option value="{{ $actor->id }}">{{ $actor->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <label class="form-label text-white">Character Name</label>
                                <input type="text" name="characters[{{ $loop->index }}][name]"
                                    class="form-control bg-dark text-white character-field" value="{{ $character->name }}">
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary" id="add-character-btn">Add More</button>
                </div>
            </div>

            <label for="director" class="form-label text-white mt-3">Director</label>
            <input type="text" name="director" id="director" class="form-control bg-dark text-white"
                value="{{ $movie->director }}">

            <label for="release_date" class="form-label text-white mt-3">Release Date</label>
            <input type="date" name="release_date" id="release_date" class="form-control bg-dark text-white"
                value="{{ $movie->release_date }}">

            <label for="thumbnail_file" class="form-label text-white mt-3">Thumbnail File</label>
            <input type="file" class="form-control bg-dark text-white" id="thumbnail_file" name="thumbnail_file">

            <label for="background_file" class="form-label text-white mt-3">Background File</label>
            <input type="file" class="form-control bg-dark text-white" id="background_file" name="background_file">

            <button class="btn btn-primary w-100 mt-5" type="submit"
                style="background-color: #C43429; border-color: #C43429">Edit</button>
        </form>
        <br>

        @if ($errors->any())
            <div class="alert alert-danger mt-5">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script type="text/javascript" src="{{ URL::asset('js/edit-movie.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
@endsection
