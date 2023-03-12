<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
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
            'cep' => fake()->countryCode(),
            'endereco' => fake()->streetAddress(),
            'numero' => fake()->numberBetween(1, 15),
            'complemento' => fake()->numberBetween(20, 30),
            'bairro' => fake()->address(),
            'cidade' => fake()->country(),
            'estado' => fake()->country()
        ];
    }
}
