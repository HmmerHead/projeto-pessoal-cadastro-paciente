<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Paciente;
use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Ramsey\Uuid\Uuid as RamseyUuid;

class PacienteTest extends TestCase
{
    public function test_criacao_paciente(): void
    {
        $uuid = (string) RamseyUuid::uuid4();
        $nascimento = date('Y-m-d H:i:s');
        $createdAt = date('Y-m-d H:i:s');

        $entity = new Paciente(
            nome: 'nome',
            nomeMae: 'nomeMae',
            cpf: '29308642064',
            nascimento: new DateTime($nascimento),
            createdAt: new DateTime($createdAt),
            id: new Uuid($uuid),
        );

        $this->assertEquals($uuid, $entity->id());
        $this->assertEquals('nome', $entity->nome);
        $this->assertEquals('nomeMae', $entity->nomeMae);
        $this->assertEquals('29308642064', $entity->cpf);
        $this->assertEquals($nascimento, $entity->nascimento());
        $this->assertEquals($createdAt, $entity->createdAt());
    }
}
