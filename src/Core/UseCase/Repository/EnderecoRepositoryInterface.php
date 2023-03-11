<?php

namespace Core\UseCase\Repository;

use Core\Domain\Entity\Endereco;

interface EnderecoRepositoryInterface
{
    public function insert(Endereco $cns): void;

    public function update(Endereco $cns): void;

    public function delete(string $pacienteId): bool;

    public function findByCep(string $cep);
}
