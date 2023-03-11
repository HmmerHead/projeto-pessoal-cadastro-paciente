<?php

namespace Core\UseCase\Repository;

use Core\Domain\Entity\Foto;
use Core\Domain\Entity\Paciente;

interface FotoRepositoryInterface
{
    public function insert(Foto $cns): void;

    public function update(Foto $cns): void;

    public function delete(string $pacienteId): bool;

    public function findByFotoPacienteId(string $pacienteId): Foto;

    public function salveFotoStorage($foto, Paciente $entityPaciente): string;

    public function removerFotoStorage(string $pacienteId): bool;
}
