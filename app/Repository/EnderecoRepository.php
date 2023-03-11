<?php

namespace App\Repository;

use App\Models\Endereco as Models;
use Core\Domain\Entity\Endereco;
use Core\UseCase\Repository\EnderecoRepositoryInterface;
use Exception;

class EnderecoRepository implements EnderecoRepositoryInterface
{
    protected $model;

    public function __construct(Models $endereco)
    {
        $this->model = $endereco;
    }

    public function insert($endereco): void
    {
        $this->model->create([
            'id' => $endereco->id(),
        ]);
    }

    public function findByCep(string $cep): Endereco
    {
        $endereco = current($this->model->where('paciente_id', $cep)->get()->toArray());

        $enderecoEntity = new Endereco(
            id: $endereco['id'],
        );

        return $this->toEnderecoEntity($enderecoEntity);
    }

    public function update($endereco): void
    {
        if (! $endedrecoDb = $this->model->find($endereco->id())) {
            throw new Exception('Endereco não encontrado');
        }

        $endedrecoDb->update([

        ]);
    }

    private function toEnderecoEntity($object): Endereco
    {
        return new Endereco(
            id: $object->id,
        );
    }

    public function delete($pacienteId): bool
    {
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}
