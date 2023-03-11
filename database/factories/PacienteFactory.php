<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'nome' => fake()->name(),
            'nomeMae' => fake()->firstNameFemale(),
            'cpf' => fake()->cpf(),
            'nascimento' => fake()->dateTimeThisYear(),
        ];
    }
}
