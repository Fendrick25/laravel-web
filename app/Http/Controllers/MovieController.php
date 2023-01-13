<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Models\Character;
use App\Models\Genre;
use App\Models\GenreType;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is.admin'], ['except' => ['index', 'show', 'indexSearch']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $movies = null;
        $sorted = null;

        if ($request->get('search')) {
            $mov = $request->get('search');
            $movies = Movie::where('title', 'LIKE', "%$mov%")->paginate(10);
        } else if ($request->genre) {
            $sorted = Movie::whereIn('id', Genre::where('genre_id', $request->genre)->get('movie_id'))->paginate(10, ['*'], 'sorted');
        } else if ($request->sort) {
            switch ($request->sort) {
                case 'latest':
                    $sorted = DB::table('movies')->orderBy('release_date')->paginate(10, ['*'], 'sorted');
                    break;
                case 'asc':
                    $sorted = DB::table('movies')->orderBy('title')->paginate(10, ['*'], 'sorted');
                    break;
                case 'desc':
                    $sorted = DB::table('movies')->orderByDesc('title')->paginate(10, ['*'], 'sorted');
                    break;
                default:
                    $sorted = Movie::paginate(10, ['*'], 'sorted');
            }
        }

        if ($movies === null) {
            $movies = Movie::paginate(5, ['*'], 'popular');
        }
        if ($sorted === null) {
            $sorted = Movie::paginate(10, ['*'], 'sorted');
        }

        return view('movies.index', [
            'movies' => $movies,
            'sorted' => $sorted,
            'genres' => GenreType::all(),
            'carousel' => $this->getRandomMovies(),
        ]);
    }

    private function getRandomMovies()
    {
        $movies = Movie::all();

        return [
            $movies->random(),
            $movies->random(),
            $movies->random(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('movies.create', [
            'actors' => Actor::all()->sortBy('name'),
            'genres' => GenreType::all()->sortBy('genre'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'actors.*.id' => ['required', 'exists:actors,id',],
            'characters.*.name' => ['required'],
            'director' => ['required', 'max:255'],
            'genres' => ['required', 'exists:genre_types,id'],
            'release_date' => ['required'],
            'thumbnail_file' => ['required'],
            'background_file' => ['required'],
        ]);

        $thumbnailFile = $request->file('thumbnail_file');
        $backgroundFile = $request->file('background_file');

        [$thumbnailUrl, $backgroundUrl] = $this->saveFiles($thumbnailFile, $backgroundFile);

        DB::transaction(function () use ($request, $thumbnailUrl, $backgroundUrl) {
            $movie = Movie::create([
                'title' => $request->title,
                'description' => $request->description,
                'release_date' => $request->release_date,
                'director' => $request->director,
                'thumbnailUrl' => $thumbnailUrl,
                'backgroundUrl' => $backgroundUrl,
            ]);

            foreach ($request->genres as $_ => $id) {
                Genre::create([
                    'movie_id' => $movie->id,
                    'genre_id' => $id,
                ]);
            }

            for ($i = 0; $i < count($request->actors); $i++) {
                Character::create([
                    'movie_id' => $movie->id,
                    'actor_id' => $request->actors[$i]['id'],
                    'name' => $request->characters[$i]['name'],
                ]);
            }
        });

        return redirect(route('home'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('movies.show', [
            'movie' => Movie::findOrFail($id),
            'movies' => Movie::paginate(10),
            'characters' => Character::where('movie_id', $id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);

        $genres = [];

        foreach ($movie->genres as $genre) {
            array_push($genres, $genre->genre);
        }

        return view('movies.edit', [
            'movie' => Movie::findOrFail($id),
            'actors' => Actor::all()->sortBy('name'),
            'genre_types' => GenreType::all()->sortBy('genre'),
            'genres' => $genres,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->id !== $id) {
            abort(400, "Bad Request");
        }

        $request->validate([
            'id' => ['required', 'exists:movies,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'max:255'],
            'actors.*.id' => ['required', 'exists:actors,id',],
            'characters.*.name' => ['required'],
            'director' => ['required', 'max:255'],
            'genres' => ['required', 'exists:genre_types,id'],
            'thumbnail_file' => ['required'],
            'background_file' => ['required'],
            'release_date' => ['required'],
        ]);

        $thumbnail_file = $request->file('thumbnail_file');
        $background_file = $request->file('background_file');

        [$thumbnailUrl, $backgroundUrl] = $this->replaceFiles($id, $thumbnail_file, $background_file);

        DB::transaction(function () use ($request, $thumbnailUrl, $backgroundUrl) {
            Movie::findOrFail($request->id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'director' => $request->director,
                'release_date' => $request->release_date,
                'thumbnailUrl' => $thumbnailUrl,
                'backgroundUrl' => $backgroundUrl,
            ]);

            Genre::where('movie_id', $request->id)->delete();

            foreach ($request->genres as $_ => $id) {
                Genre::create([
                    'movie_id' => $request->id,
                    'genre_id' => $id,
                ]);
            }

            Character::where('movie_id', $request->id)->delete();

            for ($i = 0; $i < count($request->actors); $i++) {
                Character::create([
                    'movie_id' => $request->id,
                    'actor_id' => $request->actors[$i]['id'],
                    'name' => $request->characters[$i]['name'],
                ]);
            }
        });



        return redirect(route('movies.show', ['id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Movie::destroy($id);

        return redirect(route('home'));
    }

    private function saveFiles($thumbnail_file, $background_file)
    {
        $thumbnailUrl = $thumbnail_file->getClientOriginalName() . "." . $thumbnail_file->getClientOriginalExtension();
        $backgroundUrl = $thumbnail_file->getClientOriginalName() . "." . $background_file->getClientOriginalExtension();

        Storage::putFileAs("/public/images/movies/thumbnail", $thumbnail_file, $thumbnailUrl);
        Storage::putFileAs("/public/images/movies/background", $background_file, $backgroundUrl);

        return [$thumbnailUrl, $backgroundUrl];
    }

    private function replaceFiles($id, $thumbnail_file, $background_file)
    {
        $movie = Movie::findOrFail($id);
        Storage::delete([
            "/public/images/movies/thumbnail" . $movie->thumbnailUrl,
            "/public/images/movies/background" . $movie->backgroundUrl
        ]);

        return $this->saveFiles($thumbnail_file, $background_file);
    }
}
