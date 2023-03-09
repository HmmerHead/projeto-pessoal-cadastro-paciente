<?php

namespace Core\Domain\Entity;

use Core\Domain\Traits\MetodoMagicoTrait;
use Core\Domain\ValueObject\Uuid;

class CNS
{
    use MetodoMagicoTrait;

    public function __construct(
        protected string $cnsPaciente,
        protected Uuid|string $id = '',
        protected string $paciente_id = '',

    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::generate();

        $this->isValid($this->id);
    }

    private function isValid($cnsInput)
    {
        //TODO: VALIDACAO
        return true;
    }
}
