<?php

namespace Database\Factories;

use App\Models\Niveis;
use App\Models\Desenvolvedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesenvolvedorFactory extends Factory
{
    protected $model = Desenvolvedor::class;

    public function definition()
    {
        $nivel = Niveis::factory()->create(); // Cria um novo registro no banco de dados

        return [
            'nivel_id'          => $nivel->id, // Atribui o ID do nível criado
            'nome'              => $this->faker->name,
            'sexo'              => $this->faker->randomElement(['M', 'F']),
            'data_nascimento'   => $this->faker->date(),
            'hobby'             => $this->faker->word,
        ];
    }
}
