<?php

namespace Darcher\PineconePhp;

class VectorCollectionFactory
{
    public static function create(array $values): VectorCollection
    {
        $collection = new VectorCollection();
        foreach ($values as $array) {
            list($id, $value) = $array;
            if (!is_array($value)) {
                throw new \Exception('vector value must be an array');
            }
            $collection->add(new Vector("$id", $value));
        }

        return $collection;
    }
}