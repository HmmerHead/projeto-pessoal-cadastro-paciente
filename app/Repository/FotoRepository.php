<?php

namespace App\Repository;

use App\Models\Foto as Models;
use Core\Domain\Entity\Foto;
use Core\UseCase\Repository\FotoRepositoryInterface;
use Exception;

class FotoRepository implements FotoRepositoryInterface
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
            'fotoPaciente' => $cns->fotoPaciente
        ]);
    }

    public function findByCNSPacienteId($pacienteId): Foto
    {
        $Cns = current($this->model->where('paciente_id', $pacienteId)->get()->toArray());

        $CnsEntity = new Foto(
            id: $Cns['id'],
            fotoPaciente: $Cns['fotoPaciente'],
            paciente_id: $Cns['paciente_id']
        );

        return $this->toFotoEntity($CnsEntity);
    }

    public function update($cns): void
    {
        if (!$cnsDb = $this->model->find($cns->id())) {
            throw new Exception('CNS não encontrado');
        }

        $cnsDb->update([
            'fotoPaciente' => $cns->fotoPaciente,
        ]);
    }

    private function toFotoEntity($object): Foto
    {
        return new Foto(
            id: $object->id,
            fotoPaciente: $object->fotoPaciente
        );
    }

    public function delete($pacienteId): bool
    {
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}
