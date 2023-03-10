<?php

namespace App\Repository;

use App\Models\Foto as Models;
use Core\Domain\Entity\Foto;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\FotoRepositoryInterface;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FotoRepository implements FotoRepositoryInterface
{
    const PATH = 'pacienteFoto/';

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
            'fotoPaciente' => $foto->fotoPaciente,
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
        if (! $fotoDb = $this->model->find($foto->id())) {
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

    /**
     * Undocumented function
     *
     * @param  UploadedFile  $foto
     * @param  Paciente  $pacienteId
     */
    public function salveFotoStorage($foto, $paciente): string
    {
        $path = self::PATH.$paciente->id();
        $nameFile = $paciente->nome.'.'.$foto->getClientOriginalExtension();
        $foto->storeAs($path, $nameFile);

        return $path.'/'.$nameFile;
    }

    public function removerFotoStorage($pacienteId): bool
    {
        return Storage::deleteDirectory($path = self::PATH.$pacienteId);
    }
}
