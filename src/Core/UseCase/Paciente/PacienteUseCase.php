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

    public function listarPacientes($input)
    {
        $result = ($this->repository->listPacientes(
            filter: $input->get('filter', ''), 
            order: $input->get('order', 'DESC'), 
            page: $input->get('page', 1), 
            totalPage: $input->get('totalPage', 15)
        ));

        return [
            'items' => $result->items(),
            'total' => $result->total(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'first_page' => $result->firstPage(),
            'per_page' => $result->perPage(),
            'to' => $result->to(),
            'from' => $result->from(),
        ];
    }
}
