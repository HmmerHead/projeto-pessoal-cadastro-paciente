<?php

namespace Core\UseCase\Repository;

use App\Repository\PaginationPresenter;
use Core\Domain\Entity\Paciente;

interface PacienteRepositoryInterface
{
    public function insert(Paciente $paciente): Paciente;
    public function update(Paciente $paciente): Paciente;
    public function listPaciente(string $pacienteId): Paciente;
    public function listPacientes(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationPresenter;
}
