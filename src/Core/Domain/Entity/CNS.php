<?php

namespace Core\Domain\Entity;

use Core\Domain\Traits\MetodoMagicoTrait;
use Core\Domain\ValueObject\CNS as ValurObjectCNS;
use Core\Domain\ValueObject\Uuid;

class CNS
{
    use MetodoMagicoTrait;

    public function __construct(
        protected string $cnsPaciente,
        protected string $id = '',
        protected string $paciente_id = '',

    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::generate();

        $this->isValid();
    }

    private function isValid()
    {
        new ValurObjectCNS($this->cnsPaciente);
    }
}
