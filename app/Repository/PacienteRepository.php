<?php

namespace App\Repository;

use App\Models\Paciente as Models;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\PacienteRepositoryInterface;

class PacienteRepository implements PacienteRepositoryInterface
{
    protected $model;

    public function __construct(Models $paciente)
    {
        $this->model = $paciente;
    }

    public function insert($paciente): Paciente
    {
        $modelCreated = $this->model->create([
            'id' => $paciente->id(),
            'nome' => $paciente->nome,
            'nomeMae' => $paciente->nomeMae,
            'cpf' => $paciente->cpf,
            'nascimento' => $paciente->nascimento(),
        ]);

        return $this->toPacienteEntity($modelCreated);
    }
    public function update(): array
    {
        return [];
    }
    public function listPaciente(): array
    {
        return [];
    }

    public function listPacientes(): array
    {
        return [];
    }

    private function toPacienteEntity(object $object): Paciente
    {
        return new Paciente(
            id: $object->id,
            nome: $object->nome,
            nomeMae: $object->nomeMae,
            cpf: $object->cpf,
            nascimento: $object->nascimento

        );
    }
}