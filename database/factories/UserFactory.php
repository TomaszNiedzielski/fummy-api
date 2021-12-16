<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName();

        $socials = (object) [
            'instagram' => (object) [
                'name' => $firstName,
                'link' => 'https://www.instagram.com/'.$firstName
            ],
            'tiktok' => (object) [
                'name' => $firstName,
                'link' => 'https://www.tiktok.com/@'.$firstName
            ],
            'youtube' => (object) [
                'name' => $firstName,
                'link' => 'https://www.youtube.com/c/'.$firstName
            ],
        ];

        return [
            'full_name' => $firstName.' '.$this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('qwerty123'),
            'nick' => $this->faker->userName,
            'bio' => $this->faker->text(),
            'socials' => json_encode($socials),
            'avatar' => 'https://i.pravatar.cc/500?'.$this->faker->randomNumber(5),
            'is_verified' => 1,
            'is_active' => $this->faker->boolean(80),
            'is_24_hours_delivery_on' => $this->faker->boolean(50),
        ];
    }
}
