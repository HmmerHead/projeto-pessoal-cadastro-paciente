<?php

namespace Core\Domain\Entity;

use Core\Domain\Traits\MetodoMagicoTrait;
use Core\Domain\ValueObject\Uuid;

class Foto
{
    use MetodoMagicoTrait;

    public function __construct(
        protected string $fotoPaciente,
        protected string $id = '',
        protected string $paciente_id = '',

    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::generate();
    }
}
