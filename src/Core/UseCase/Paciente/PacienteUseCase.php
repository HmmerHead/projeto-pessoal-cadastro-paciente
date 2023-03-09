<?php

namespace Core\UseCase\Paciente;

use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PacienteUseCase
{
    protected $repository;

    public function __construct(PacienteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function salvarPaciente($input)
    {
        try {
            $entity = new Paciente(
                id: '',
                nome: $input['nome'],
                nomeMae: $input['nomeMae'],
                cpf: $input['cpf'],
                nascimento: Date::parse($input['nascimento'])
            );

            DB::commit();

            return $this->repository->insert($entity);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function alterarPaciente($input, $id)
    {
        try {
            $entity = new Paciente(
                id: $id,
                nome: $input['nome'],
                nomeMae: $input['nomeMae'],
                cpf: $input['cpf'],
                nascimento: Date::parse($input['nascimento'])
            );

            DB::commit();
            
            return $this->repository->update($entity);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
