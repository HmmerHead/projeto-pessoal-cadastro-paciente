<?php

namespace Core\Domain\Entity;

use Core\Domain\Traits\MetodoMagicoTrait;
use Core\Domain\ValueObject\CNS as ValurObjectCNS;
use Core\Domain\ValueObject\Uuid;

class Endereco
{
    use MetodoMagicoTrait;
    public function __construct(
        protected string $id = '',
        protected string $cep = '',
        protected string $endereco = '',
        protected string $numero = '',
        protected string $complemento = '',
        protected string $bairro = '',
        protected string $cidade = '',
        protected string $estado = '',
        protected string $paciente_id = '',

    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::generate();
    }
}
