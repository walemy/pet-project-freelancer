<?php

namespace Freelance\User\Infrastructure\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('12345678'),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function email(string $email)
    {
        return $this->state(fn(array $attributes) => [
            'email' => $email,
        ]
        );
    }

    public function password(string $password)
    {
        return $this->state(fn(array $attributes) => [
            'password' => bcrypt($password),
        ]
        );
    }
}