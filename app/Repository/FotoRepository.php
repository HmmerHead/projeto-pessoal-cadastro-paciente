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

    public function insert($foto): void
    {
        $this->model->create([
            'id' => $foto->id(),
            'paciente_id' => $foto->paciente_id,
            'fotoPaciente' => $foto->fotoPaciente
        ]);
    }

    public function findByCNSPacienteId($pacienteId): Foto
    {
        $foto = current($this->model->where('paciente_id', $pacienteId)->get()->toArray());

        $fotoEntity = new Foto(
            id: $foto['id'],
            fotoPaciente: $foto['fotoPaciente'],
            paciente_id: $foto['paciente_id']
        );

        return $this->toFotoEntity($fotoEntity);
    }

    public function update($foto): void
    {
        if (!$fotoDb = $this->model->find($foto->id())) {
            throw new Exception('Foto não encontrado');
        }

        $fotoDb->update([
            'fotoPaciente' => $foto->fotoPaciente,
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
