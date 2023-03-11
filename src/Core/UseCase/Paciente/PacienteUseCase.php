<?php

namespace Core\UseCase\Paciente;

use Core\Domain\Entity\CNS;
use Core\Domain\Entity\Foto;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\CNSRepositoryInterface;
use Core\UseCase\Repository\FotoRepositoryInterface;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PacienteUseCase
{
    protected $repositoryPaciente;

    protected $repositoryCns;

    protected $repositoryFoto;

    public function __construct(
        PacienteRepositoryInterface $repositoryPaciente,
        FotoRepositoryInterface $repositoryFoto,
        CNSRepositoryInterface $repositoryCns
    ) {
        $this->repositoryPaciente = $repositoryPaciente;
        $this->repositoryFoto = $repositoryFoto;
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

            $this->cadastrarCNS($persistedPaciente, $input);

            $this->cadastrarFoto($persistedPaciente, $entityPaciente, $input);

            DB::commit();

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

            $this->editarCNS($updatedPaciente, $input);

            $this->editarFoto($updatedPaciente, $input);

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
            filter: $input->get('filter', []),
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
            DB::beginTransaction();

            $this->repositoryPaciente->delete($input->id);

            $this->repositoryCns->delete($input->id);

            $this->repositoryFoto->delete($input->id);

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function cadastrarCNS($persistedPaciente, $input): void
    {
        $entityCns = new CNS(
            cnsPaciente: (string) $input['cns'],
            paciente_id: $persistedPaciente->id()
        );

        $this->repositoryCns->insert($entityCns);
    }

    private function cadastrarFoto($persistedPaciente, $entityPaciente, $input): void
    {
        $path = $this->repositoryFoto->salveFotoStorage($input['foto'], $entityPaciente);

        $entityFoto = new Foto(
            fotoPaciente: $path,
            paciente_id: $persistedPaciente->id()
        );

        $this->repositoryFoto->insert($entityFoto);
    }

    private function editarCNS($updatedPaciente, $input): void
    {
        $CNSOfUpdatedPaciente = $this->repositoryCns->findByCNSPacienteId($updatedPaciente->id);

        $entityCns = new CNS(
            id: $CNSOfUpdatedPaciente->id(),
            paciente_id: $updatedPaciente->id(),
            cnsPaciente: (string) $input['cns']
        );

        $this->repositoryCns->update($entityCns);
    }

    private function editarFoto($updatedPaciente, $input): void
    {
        $FotoOfUpdatedPaciente = $this->repositoryFoto->findByCNSPacienteId($updatedPaciente->id);

        $this->repositoryFoto->removerFotoStorage($updatedPaciente->id);

        $path = $this->repositoryFoto->salveFotoStorage($input['foto'], $updatedPaciente);

        $entityFoto = new Foto(
            id: $FotoOfUpdatedPaciente->id(),
            paciente_id: $updatedPaciente->id(),
            fotoPaciente: $path
        );

        $this->repositoryFoto->update($entityFoto);
    }
}
