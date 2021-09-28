<?php

namespace Database\Factories;

use App\Models\album;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = album::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=> User::factory(),
            'title'=> $this->faker->sentence,
            'description'=> $this->faker->words(10, true),
        ];
    }
}
