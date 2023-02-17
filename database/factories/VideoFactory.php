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

    private $id = 1;
    private $userId = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id = $this->id;
        $userId = $this->userId;

        $this->id = $id + 1;
        $this->userId = $userId < 30 ? $userId + 1 : 1;

        return [
            'user_id' => $userId,
            'name' => $this->faker->uuid().'.mp4',
            'thumbnail' => 'https://source.unsplash.com/random/500x700?sig='.$this->faker->unique()->randomNumber(5),
            'order_id' => $id,
            'processing_complete' => 1
        ];
    }
}