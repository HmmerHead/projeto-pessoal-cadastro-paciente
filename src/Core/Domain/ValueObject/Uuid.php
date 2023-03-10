<?php

namespace Core\Domain\ValueObject;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function __construct(
        protected string $value
    ) {
        $this->isValid($value);
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isValid(string $id): void
    {
        if (! RamseyUuid::isValid($id)) {
            throw new Exception(sprintf('Valor informado não permitido: <%s> - <%s>.', static::class, $id));
        }
    }
}
