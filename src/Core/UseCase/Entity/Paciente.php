<?php

namespace Core\Usecase\Entity;

use Core\Domain\ValueObject\Documento;
use Core\UseCase\Traits\MetodoMagicoTrait;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Paciente
{
    use MetodoMagicoTrait;

    public function __construct(
        protected ?Uuid $id,
        protected string $nome,
        protected string $nomeMae,
        protected string $cpf,
        protected DateTime $nascimento,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $deletedAt = null,

    ) {
        $this->id = $this->id ?? Uuid::generate();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->isValid();
    }

    private function isValid()
    {
        new Documento($this->cpf);
    }
}
