<?php

namespace App\Repository;

use App\Models\Paciente as Models;
use App\Repository\PaginationPresenter;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Exception;

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
    public function update($paciente): Paciente
    {
        if(!$pacienteDb = $this->model->find($paciente->id())){
            throw new Exception('Paciente não encontrado');
        }

        $pacienteDb->update([
            'nome' => $paciente->nome,
            'nomeMae' => $paciente->nomeMae,
            'cpf' => $paciente->cpf,
            'nascimento' => $paciente->nascimento(),
        ]);

        $pacienteDb->refresh();
        
        return $this->toPacienteEntity($pacienteDb);
    }
    public function listPaciente($pacienteId): Paciente
    {
        if (!$pacienteDB = $this->model->find($pacienteId)) {
            throw new Exception('Paciente não encontrado');
        }

        return $this->toPacienteEntity($pacienteDB);
    }

    public function listPacientes(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationPresenter
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query = $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
        
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

    public function delete($pacienteId): bool
    {
        if (!$pacienteDb = $this->model->find($pacienteId)) {
            throw new Exception('Category Not Found');
        }
        
        return $pacienteDb->delete();
    }
}