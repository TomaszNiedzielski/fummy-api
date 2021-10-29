<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(2),
            'title' => $this->faker->text(20),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomNumber(3),
            'currency' => 'PLN',
        ];
    }
}