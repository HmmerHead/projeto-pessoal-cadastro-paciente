<?php

namespace Tests\Unit\Domain\ValueObject;

use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidTest extends TestCase
{
    public function test_uuid_is_valid(): void
    {
        $this->assertTrue(RamseyUuid::isValid(ValueObjectUuid::generate()));
    }

    public function test_uuid_is_generated(): void
    {
        $this->assertTrue(RamseyUuid::isValid(new ValueObjectUuid(RamseyUuid::uuid4()->toString())));
    }

    public function test_uuid_is_invalid(): void
    {
        try {
            new ValueObjectUuid('invalid');
            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(Exception::class, $th);
        }
    }
}
