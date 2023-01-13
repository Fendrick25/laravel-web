<?php

namespace Database\Seeders;

use App\Models\Movie;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            Movie::create([
                "title" => "Example Title",
                "description" => $faker->text(255),
                "release_date" => $faker->date(),
                "thumbnailUrl" => "default.jpeg",
                "backgroundUrl" => "default.jpeg",
                "director" => $faker->name(),
            ]);
        }
    }
}
