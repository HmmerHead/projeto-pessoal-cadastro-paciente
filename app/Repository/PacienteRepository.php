<?php

namespace App\Repository;

use App\Models\Paciente as Models;
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

    /**
     * Insert da Entity paciente
     *
     * @param  Paciente  $paciente
     */
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

    /**
     * Update da Entity paciente
     *
     * @param  Paciente  $paciente
     */
    public function update($paciente): Paciente
    {
        if (! $pacienteDb = $this->model->find($paciente->id())) {
            throw new Exception('Paciente não encontrado');
        }

        $pacienteDb->update([
            'nome' => $paciente->nome,
            'nomeMae' => $paciente->nomeMae,
            'cpf' => $paciente->cpf,
            'nascimento' => $paciente->nascimento(),
        ]);

        return $this->toPacienteEntity($pacienteDb);
    }

    /**
     * Lista um paciente
     *
     * @param  Paciente  $pacienteId
     */
    public function listPaciente($pacienteId): Paciente
    {
        if (! $pacienteDB = $this->model->find($pacienteId)) {
            throw new Exception('Paciente não encontrado');
        }

        return $this->toPacienteEntity($pacienteDB);
    }

    /**
     * Lista varios pacientes
     *
     * @param  array  $filter
     * @param  string  $order
     */
    public function listPacientes($filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationPresenter
    {
        $query = $this->model;
        if ($filter) {
            $value = current($filter);
            if (array_key_exists('nome', $filter) && ! empty($value)) {
                $query = $query->where('nome', 'LIKE', "%{$value}%");
            }

            if (array_key_exists('cpf', $filter) && ! empty($value)) {
                $query = $query->where('cpf', 'LIKE', "%{$value}%");
            }
        }
        $query = $query->orderBy('id', $order);

        $paginator = $query->paginate($totalPage);

        return new PaginationPresenter($paginator);
    }

    /**
     * Transforma em um entity
     *
     * @param  Paciente  $object
     */
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

    /**
     * Delete um paciente
     *
     * @param  string  $pacienteId
     */
    public function delete($pacienteId): bool
    {
        if (! $pacienteDb = $this->model->find($pacienteId)) {
            throw new Exception('Category Not Found');
        }

        return $pacienteDb->delete();
    }
}
