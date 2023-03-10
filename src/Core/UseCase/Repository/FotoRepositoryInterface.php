<?php

namespace Core\UseCase\Repository;

use Core\Domain\Entity\Foto;

interface FotoRepositoryInterface
{
    public function insert(Foto $cns): void;
    public function update(Foto $cns): void;
    public function delete(string $pacienteId): bool;
    public function findByCNSPacienteId(string $pacienteId): Foto;
}
