<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    private $id = 1;

    public function definition()
    {
        return [
            'rate' => 5,
            'client_name' => $this->faker->firstName(),
            'text' => $this->faker->text(200),
            'video_id' => $this->getId(),
            'access_key' => $this->faker->uuid(),
        ];
    }

    private function getId(): int
    {
        $id = $this->id;
        $this->id += 1;

        return $id;
    }
}
