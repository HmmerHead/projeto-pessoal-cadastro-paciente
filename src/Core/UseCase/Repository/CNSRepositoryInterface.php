<?php

namespace Core\UseCase\Repository;

use Core\Domain\Entity\CNS;

interface CNSRepositoryInterface
{
    public function insert(CNS $cns): void;
    public function update(CNS $cns): void;
    public function delete(string $cnsId): bool;
    public function listCns(string $cnsId): CNS;
    public function findByCNSPacienteId(string $pacientevvId): CNS;
}
