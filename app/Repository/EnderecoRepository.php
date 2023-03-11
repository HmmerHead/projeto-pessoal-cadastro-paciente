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
            'cep' => $endereco->cep,
            'endereço' => $endereco->endereço,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'estado' => $endereco->estado,
            'paciente_id' => $endereco->paciente_id,
        ]);
    }

    public function findByEnderecoByPacienteId(string $paciente_id): Endereco
    {
        $endereco = current($this->model->where('paciente_id', $paciente_id)->get()->toArray());

        $enderecoEntity = new Endereco(
            id: $endereco['id'],
            cep: $endereco['cep'],
            endereço: $endereco['endereço'],
            numero: $endereco['numero'],
            complemento: $endereco['complemento'],
            bairro: $endereco['bairro'],
            cidade: $endereco['cidade'],
            estado: $endereco['estado'],
            paciente_id: $endereco['paciente_id'],
        );

        return $this->toEnderecoEntity($enderecoEntity);
    }

    public function update($endereco): void
    {
        if (! $endedrecoDb = $this->model->find($endereco->id())) {
            throw new Exception('Endereco não encontrado');
        }

        $endedrecoDb->update([
            'id' => $endereco->id(),
            'cep' => $endereco->cep,
            'endereço' => $endereco->endereço,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'estado' => $endereco->estado,
            'paciente_id' => $endereco->paciente_id,
        ]);
    }

    private function toEnderecoEntity($object): Endereco
    {
        return new Endereco(
            id: $object->id,
            cep: $object->cep,
            endereço: $object->endereço,
            numero: $object->numero,
            complemento: $object->complemento,
            bairro: $object->bairro,
            cidade: $object->cidade,
            estado: $object->estado,
            paciente_id: $object->paciente_id,
        );
    }

    public function delete($pacienteId): bool
    {
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}
