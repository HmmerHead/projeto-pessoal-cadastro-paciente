<?php

namespace App\Repository;

use App\Models\CNS as Models;
use App\Repository\PaginationPresenter;
use Core\Domain\Entity\CNS;
use Core\UseCase\Repository\CNSRepositoryInterface;
use Exception;

class CNSRepository implements CNSRepositoryInterface
{
    protected $model;

    public function __construct(Models $cns)
    {
        $this->model = $cns;
    }

    public function insert($cns): void
    {
        $this->model->create([
            'id' => $cns->id(),
            'paciente_id' => $cns->paciente_id,
            'cnsPaciente' => $cns->cnsPaciente
        ]);
    }

    public function findByCNSPacienteId($pacienteId): CNS
    {
        $Cns = current($this->model->where('paciente_id', $pacienteId)->get()->toArray());

        $CnsEntity = new CNS(
            id: $Cns['id'],
            cnsPaciente: $Cns['cnsPaciente'],
            paciente_id: $Cns['paciente_id']
        );

        return $this->toCnsEntity($CnsEntity);
    }

    public function update($cns): void
    {
        if(!$cnsDb = $this->model->find($cns->id())){
            throw new Exception('CNS não encontrado');
        }

        $cnsDb->update([
            'cnsPaciente' => $cns->cnsPaciente,
        ]);
    }

    public function listCns($cnsId): CNS
    {
        return $this->toCnsEntity();
    }

    private function toCnsEntity($object): CNS
    {
        return new CNS(
            id: $object->id,
            cnsPaciente: $object->cnsPaciente
        );

    }

    public function delete($pacienteId): bool
    {       
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}