<?php

namespace Darcher\PineconePhp\Tests\Integration;

use Darcher\PineconePhp\PindeconeIndex;
use PHPUnit\Framework\TestCase;

class PineconeIndexTest extends TestCase
{
    public function test_create_index()
    {
        $client = new PindeconeIndex('47d04139-a288-45db-9993-7d4c1337a2b7');
        $indexName = mt_rand();
        $result = $client->createIndex($indexName);
        $this->assertTrue($result);
    }
}