<?php

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\ValueObject\CNS as ValueObjectCNS;
use Exception;
use PHPUnit\Framework\TestCase;

class CNSTest extends TestCase
{
    public function test_cns_valido(): void
    {
        $this->assertEquals(new ValueObjectCNS('153638180670009'), '153638180670009');
    }

    public function test_cns_invalido(): void
    {
        try {
            new ValueObjectCNS('23724435445');
            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(Exception::class, $th);
        }
    }
}