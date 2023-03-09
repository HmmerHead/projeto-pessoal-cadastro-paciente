<?php

namespace Core\Domain\ValueObject;

class CNS
{
    public function __construct(
        protected string $value
    ) {
        $this->isValid($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isValid(string $id)
    {
        return '';
    }
}