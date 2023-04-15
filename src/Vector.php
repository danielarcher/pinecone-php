<?php

namespace Darcher\PineconePhp;

class Vector
{
    public string $id;
    public array $values;
    public $metadata;
    public $sparseValues;

    public function __construct(string $id, array $values, $metadata = null, $sparseValues = null)
    {
        $this->id = $id;
        $this->values = $values;
        $this->metadata = $metadata;
        $this->sparseValues = $sparseValues;
    }
}