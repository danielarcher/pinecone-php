<?php

namespace Darcher\PineconePhp;

class VectorCollection
{
    public array $vectors;

    /**
     * @param \Darcher\PineconePhp\Vector[] $vectors
     */
    public function __construct(array $vectors = [])
    {
        $this->vectors = $vectors;
    }

    public function add(Vector $vector): void
    {
        $this->vectors[] = $vector;
    }

    public function remove(Vector $vector): void
    {
        $key = array_search($vector, $this->vectors, true);
        if ($key !== false) {
            unset($this->vectors[$key]);
        }
    }

    public function get(int $index): ?Vector
    {
        return $this->vectors[$index] ?? null;
    }

    public function count(): int
    {
        return count($this->vectors);
    }
}
