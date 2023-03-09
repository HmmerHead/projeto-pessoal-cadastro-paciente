<?php

namespace Core\UseCase\Repository;

use Core\Domain\Entity\Paciente;

interface PacienteRepositoryInterface
{
    public function insert(Paciente $paciente): Paciente;
    public function update(): array;
    public function listPaciente(): array;
    public function listPacientes(): array;
}
