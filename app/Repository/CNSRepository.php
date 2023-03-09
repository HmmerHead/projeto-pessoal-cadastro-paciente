<?php

namespace App\Repository;

use App\Models\CNS as Models;
use App\Repository\PaginationPresenter;
use Core\Domain\Entity\CNS;
use Core\UseCase\Repository\CNSRepositoryInterface;
use Exception;

class CNSRepository implements CNSRepositoryInterface
{
    protected $model;

    public function __construct(Models $cns)
    {
        $this->model = $cns;
    }

    public function insert($cns): void
    {
        $this->model->create([
            'id' => $cns->id(),
            'paciente_id' => $cns->paciente_id,
            'cnsPaciente' => $cns->cnsPaciente
        ]);
    }
    public function update($cns): void
    {
        return $this->toCnsEntity();
    }
    public function listCns($cnsId): CNS
    {
        return $this->toCnsEntity();
    }

    private function toCnsEntity(object $object): CNS
    {
        return new CNS(
            id: $object->id,
            cnsPaciente: (string) $object->cnsPacient
        );
    }

    public function delete($cnsId): bool
    {
        return true;
    }
}