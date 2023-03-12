<?php

namespace Database\Seeders;

use App\Models\CNS;
use App\Models\Endereco;
use App\Models\Foto;
use App\Models\Paciente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PacienteSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paciente = Paciente::factory()->createOneQuietly();

        CNS::factory()
            ->count(1)
            ->for($paciente)
            ->create();

        Foto::factory()
            ->count(1)
            ->for($paciente)
            ->create();

        Endereco::factory()
            ->count(1)
            ->for($paciente)
            ->create();
    }
}
