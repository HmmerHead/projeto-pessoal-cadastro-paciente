<?php

namespace Core\UseCase\Paciente;

use Core\Domain\Entity\CNS;
use Core\Domain\Entity\Endereco;
use Core\Domain\Entity\Foto;
use Core\Domain\Entity\Paciente;
use Core\UseCase\Repository\CNSRepositoryInterface;
use Core\UseCase\Repository\EnderecoRepositoryInterface;
use Core\UseCase\Repository\FotoRepositoryInterface;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PacienteUseCase
{
    protected $repositoryPaciente;

    protected $repositoryCns;

    protected $repositoryFoto;

    protected $repositoryEndereco;

    public function __construct(
        PacienteRepositoryInterface $repositoryPaciente,
        FotoRepositoryInterface $repositoryFoto,
        CNSRepositoryInterface $repositoryCns,
        EnderecoRepositoryInterface $repositoryEndereco
    ) {
        $this->repositoryPaciente = $repositoryPaciente;
        $this->repositoryFoto = $repositoryFoto;
        $this->repositoryCns = $repositoryCns;
        $this->repositoryEndereco = $repositoryEndereco;
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

            $this->cadastrarEndereco($persistedPaciente, $input);

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

            $this->editarEndereco($updatedPaciente, $input);

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

            $this->repositoryEndereco->delete($input->id);

            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function cadastrarEndereco($persistedPaciente, $input)
    {
        $cep = $input['cep'];

        if (! preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
            throw new Exception('CEP invalido');
        }

        $entityEndereco = new Endereco(
            cep: $input['cep'],
            endereco: $input['endereco'],
            numero: $input['numero'],
            complemento: $input['complemento'],
            bairro: $input['bairro'],
            cidade: $input['cidade'],
            estado: $input['estado'],
            paciente_id: $persistedPaciente->id,
        );

        $this->repositoryEndereco->insert($entityEndereco);
    }

    private function editarEndereco($persistedPaciente, $input)
    {
        $cep = $input['cep'];

        if (! preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
            throw new Exception('CEP invalido');
        }

        $enderecoByPaciente = $this->repositoryEndereco->findByEnderecoByPacienteId($persistedPaciente->id);

        $entityEndereco = new Endereco(
            id: $enderecoByPaciente->id,
            cep: $input['cep'] ?? $enderecoByPaciente->cep,
            endereco: $input['endereco'] ?? $enderecoByPaciente->endereco,
            numero: $input['numero'] ?? $enderecoByPaciente->numero,
            complemento: $input['complemento'] ?? $enderecoByPaciente->complemento,
            bairro: $input['bairro'] ?? $enderecoByPaciente->bairro,
            cidade: $input['cidade'] ?? $enderecoByPaciente->cidade,
            estado: $input['estado'] ?? $enderecoByPaciente->estado,
            paciente_id: $persistedPaciente->id,
        );

        $this->repositoryEndereco->update($entityEndereco);
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
        if ($input['foto'] instanceof UploadedFile) {
            $path = $this->repositoryFoto->salveFotoStorage($input['foto'], $entityPaciente);

            $entityFoto = new Foto(
                fotoPaciente: $path,
                paciente_id: $persistedPaciente->id()
            );

            $this->repositoryFoto->insert($entityFoto);
        }
    }

    private function editarCNS($updatedPaciente, $input): void
    {
        $CNSOfUpdatedPaciente = $this->repositoryCns->findByCNSPacienteId($updatedPaciente->id, $input);

        $entityCns = new CNS(
            id: $CNSOfUpdatedPaciente->id(),
            paciente_id: $updatedPaciente->id(),
            cnsPaciente: (string) $input['cns'] ?? $CNSOfUpdatedPaciente->cns
        );

        $this->repositoryCns->update($entityCns);
    }

    private function editarFoto($updatedPaciente, $input): void
    {
        $FotoOfUpdatedPaciente = $this->repositoryFoto->findByFotoPacienteId($updatedPaciente->id);

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
