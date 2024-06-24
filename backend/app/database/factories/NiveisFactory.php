<?php

namespace Database\Factories;

use App\Models\Niveis;
use Illuminate\Database\Eloquent\Factories\Factory;

class NiveisFactory extends Factory
{
    protected $model = Niveis::class;

    public function definition()
    {
        return [
            'nivel' => $this->faker->word,
        ];
    }
}
