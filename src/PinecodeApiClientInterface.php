<?php

namespace Darcher\PineconePhp;

interface PinecodeApiClientInterface
{
    public function createCollection(string $collectionName, int $dimensions): array;

    public function insertVector(string $collectionName, string $vectorId, array $vector): array;

    public function search(string $collectionName, array $queryVector, int $topK): array;

    public function updateVector(string $collectionName, string $vectorId, array $newVector): array;

    public function deleteVector(string $collectionName, string $vectorId): array;

    public function deleteCollection(string $collectionName): array;
}
