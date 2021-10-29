<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(2),
            'name' => $this->faker->uuid().'.mp4',
            'thumbnail' => 'https://source.unsplash.com/random/500x700?sig='.$this->faker->unique()->randomNumber(5),
            'order_id' => $this->faker->unique()->randomNumber(3),
            'processing_complete' => 1
        ];
    }
}