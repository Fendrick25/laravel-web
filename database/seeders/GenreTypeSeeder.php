<?php

namespace Database\Seeders;

use App\Models\GenreType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            "Comedy",
            "Action",
            "Horror",
            "Adventure",
            "SCI-FI",
            "Romance",
            "Documentation",
            "Mystery"
        ];


        foreach ($genres as $genre) {
            GenreType::create([
                "genre" => $genre,
            ]);
        }
    }
}
