<?php

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\ValueObject\Documento;
use Exception;
use PHPUnit\Framework\TestCase;

class DocumentoTest extends TestCase
{
    public function test_documento_valido(): void
    {
        $this->assertEquals(new Documento('29308642064'), '29308642064');
    }

    public function test_documento_invalido(): void
    {
        try {
            new Documento('invalido');
            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(Exception::class, $th);
        }
    }
}
