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

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // It should be deleted after refacting "video.user_id"
        $id = $this->getId();
        $userId = $id;

        if ($id > 1200) {
            $userId = intval($id / 7);
        }

        if ($id > 700 && $id <= 1200) {
            $userId = intval($id / 5);
        }

        if ($id > 299 && $id <= 700) {
            $userId = intval($id / 3);
        }

        return [
            'user_id' => $userId,
            'name' => $this->faker->uuid().'.mp4',
            'thumbnail' => 'https://source.unsplash.com/random/500x700?sig='.$this->faker->unique()->randomNumber(5),
            'order_id' => $id,
            'processing_complete' => 1
        ];
    }

    private function getId(): int
    {
        $id = $this->id;
        $this->id += 1;

        return $id;
    }
}