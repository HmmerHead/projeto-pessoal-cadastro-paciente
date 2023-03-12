<?php

namespace App\Repository;

use App\Models\CNS as Models;
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

    /**
     * Insert da Entity Foto
     *
     * @param [type] $cns
     */
    public function insert($cns): void
    {
        $this->model->create([
            'id' => $cns->id(),
            'paciente_id' => $cns->paciente_id,
            'cnsPaciente' => $cns->cnsPaciente,
        ]);
    }

    /**
     * Acha a foto usando o ID do paciente
     *
     * @param  string  $pacienteId
     */
    public function findByCNSPacienteId($pacienteId, $input): CNS
    {
        $cns = $this->model->where('paciente_id', $pacienteId)->first();

        if ($cns) {
            $cns = $cns->toArray();
            $CnsEntity = new CNS(
                id: $cns['id'],
                cnsPaciente: $cns['cnsPaciente'],
                paciente_id: $cns['paciente_id']
            );

            return $this->toCnsEntity($CnsEntity);
        }

        return new CNS(
            cnsPaciente: $input['cns'],
            paciente_id: $pacienteId
        );
    }

    /**
     * Update da Entity CNS
     *
     * @param  CNS  $cns
     */
    public function update($cns): void
    {
        if (! $cnsDb = $this->model->find($cns->id())) {
            throw new Exception('CNS não encontrado');
        }

        $cnsDb->update([
            'cnsPaciente' => $cns->cnsPaciente,
        ]);
    }

    /**
     * Transforma em um entity
     *
     * @param  CNS  $object
     */
    private function toCnsEntity($object): CNS
    {
        return new CNS(
            id: $object->id,
            cnsPaciente: $object->cnsPaciente,
            paciente_id: $object->paciente_id
        );
    }

    /**
     * Delete um CNS
     *
     * @param [type] $pacienteId
     */
    public function delete($pacienteId): bool
    {
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}
