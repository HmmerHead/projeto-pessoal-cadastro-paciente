<?php

namespace Core\UseCase\Paciente;

use Core\Domain\Entity\CNS;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\CNSRepositoryInterface;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PacienteUseCase
{
    protected $repositoryPaciente;
    protected $repositoryCns;

    public function __construct(
        PacienteRepositoryInterface $repositoryPaciente,
        CNSRepositoryInterface $repositoryCns
        )
    {
        $this->repositoryPaciente = $repositoryPaciente;
        $this->repositoryCns = $repositoryCns;
    }

    public function salvarPaciente($input)
    {
        try {
            $entityPaciente = new Paciente(
                id: '',
                nome: $input['nome'],
                nomeMae: $input['nomeMae'],
                cpf: $input['cpf'],
                nascimento: Date::parse($input['nascimento'])
            );

            DB::beginTransaction();

            $persistedPaciente = $this->repositoryPaciente->insert($entityPaciente);

            $entityCns = new CNS(
                cnsPaciente: (string) $input['cns'],
                paciente_id: $persistedPaciente->id()
            );

            $this->repositoryCns->insert($entityCns);

            DB::commit();

            // TODO: adaptar para retornar todos os dados
            return $persistedPaciente;


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function alterarPaciente($input, $id)
    {
        try {
            $entity = new Paciente(
                id: $id,
                nome: $input['nome'],
                nomeMae: $input['nomeMae'],
                cpf: $input['cpf'],
                nascimento: Date::parse($input['nascimento'])
            );

            DB::beginTransaction();

            $updatedPaciente = $this->repositoryPaciente->update($entity);

            // TODO Cache
            $CNSOfUpdatedPaciente = $this->repositoryCns->findByCNSPacienteId($updatedPaciente->id);

            $entityCns = new CNS(
                id: $CNSOfUpdatedPaciente->id(),
                paciente_id: $updatedPaciente->id(),
                cnsPaciente: (string) $input['cns']
            );

            $this->repositoryCns->update($entityCns);

            DB::commit();

            return $updatedPaciente;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function listarPacientes($input)
    {
        $result = $this->repositoryPaciente->listPacientes(
            filter: $input->get('filter', ''), 
            order: $input->get('order', 'DESC'), 
            page: $input->get('page', 1), 
            totalPage: $input->get('totalPage', 15)
        );

        return [
            'items' => $result->items(),
            'total' => $result->total(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'first_page' => $result->firstPage(),
            'per_page' => $result->perPage(),
            'to' => $result->to(),
            'from' => $result->from(),
        ];
    }

    public function listarPaciente($input)
    {
        return $this->repositoryPaciente->listPaciente($input->id);
    }

    public function deletarPaciente($input): bool
    {
        try {
            DB::commit();
            return $this->repositoryPaciente->delete($input->id);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
