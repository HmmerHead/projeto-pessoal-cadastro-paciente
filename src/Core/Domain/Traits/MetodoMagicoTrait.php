<?php

namespace Core\Domain\Traits;

use Exception;

trait MetodoMagicoTrait
{
    public function __get($property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new Exception("Property {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function nascimento(): string
    {
        return $this->nascimento->format('Y-m-d H:i:s');
    }
}
