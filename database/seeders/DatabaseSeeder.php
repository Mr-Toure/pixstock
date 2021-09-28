<?php

namespace Database\Seeders;

use App\Models\album;
use App\Models\Photo;
use App\Models\Source;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)
            ->has(Album::factory(2)
                ->has(Photo::factory()->count(3)
                    ->state(new Sequence(
                        ['active' => '1'],
                        ['active' => '0']
                    ))
                    ->has(Source::factory()->count(1))
                )
            )->create();
    }
}
