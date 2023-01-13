<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Character;
use App\Models\Movie;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $totalActor = Actor::count();

        foreach (Movie::all() as $movie) {
            for ($i = 0; $i < 3; $i++) {
                Character::create([
                    'actor_id' => $faker->numberBetween(1, $totalActor),
                    'movie_id' => $movie->id,
                    'name' => $faker->name(),
                ]);
            }
        }
    }
}
