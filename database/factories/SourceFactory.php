<?php

namespace Database\Factories;

use App\Models\Source;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Source::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $width = 640; $height = 480;
        $path = $this->faker->image('public/storage/photos', $width, $height, null, true, true, null, false);
        return [
            'photo_id'=> Photo::factory(),
            'path' => $path,
            'url'=> config('app.url').'/'.Str::after($path, 'public/'),
            'size' => rand(1111, 9999),
            'width' => $width,
            'height' => $height,
        ];
    }
}
