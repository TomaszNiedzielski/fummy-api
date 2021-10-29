<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'offer_id' => $this->faker->randomNumber(3),
            'purchaser_name' => $this->faker->name,
            'purchaser_email' => $this->faker->email,
            'instructions' => $this->faker->text(200),
            'is_private' => 0,
            'deadline' => '2021-10-10 10:10:10',
        ];
    }
}
