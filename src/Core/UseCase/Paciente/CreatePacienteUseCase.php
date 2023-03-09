<?php

namespace Core\UseCase\Paciente;

use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Illuminate\Support\Facades\Date;

class CreatePacienteUseCase
{
    protected $repository;

    public function __construct(PacienteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function salvarPaciente($input)
    {
        $entity = new Paciente(
            id: '',
            nome: $input['nome'],
            nomeMae: $input['nomeMae'],
            cpf: $input['cpf'],
            nascimento: Date::parse($input['nascimento'])
        );

        return $this->repository->insert($entity);
    }
}