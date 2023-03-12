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

    /**
     * Insert da Entity Foto
     *
     * @param  Endereco  $endereco
     */
    public function insert($endereco): void
    {
        $this->model->create([
            'id' => $endereco->id(),
            'cep' => $endereco->cep,
            'endereco' => $endereco->endereco,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'estado' => $endereco->estado,
            'paciente_id' => $endereco->paciente_id,
        ]);
    }

    /**
     * Acha a foto usando o ID do paciente
     */
    public function findByEnderecoByPacienteId(string $paciente_id): Endereco
    {
        $endereco = $this->model->where('paciente_id', $paciente_id)->first();

        if ($endereco) {
            $endereco = $endereco->toArray();

            $enderecoEntity = new Endereco(
                id: $endereco['id'],
                cep: $endereco['cep'],
                endereco: $endereco['endereco'],
                numero: $endereco['numero'],
                complemento: $endereco['complemento'],
                bairro: $endereco['bairro'],
                cidade: $endereco['cidade'],
                estado: $endereco['estado'],
                paciente_id: $endereco['paciente_id'],
            );

            return $enderecoEntity;
        }

        return new Endereco(
            paciente_id: $paciente_id,
        );
    }

    /**
     * Update da Entity Foto
     *
     * @param  Endereco  $endereco
     */
    public function update($endereco): void
    {
        if (! $endedrecoDb = $this->model->find($endereco->id())) {
            throw new Exception('Endereco não encontrado');
        }

        $endedrecoDb->update([
            'id' => $endereco->id(),
            'cep' => $endereco->cep,
            'endereco' => $endereco->endereco,
            'numero' => $endereco->numero,
            'complemento' => $endereco->complemento,
            'bairro' => $endereco->bairro,
            'cidade' => $endereco->cidade,
            'estado' => $endereco->estado,
            'paciente_id' => $endereco->paciente_id,
        ]);
    }

    /**
     * Delete um Foto
     *
     * @param  string  $pacienteId
     */
    public function delete($pacienteId): bool
    {
        return $this->model->where('paciente_id', $pacienteId)->delete();
    }
}
