<?php

namespace Darcher\PineconePhp\Tests\Integration;

use Darcher\PineconePhp\IndexApi;
use Darcher\PineconePhp\Vector;
use Darcher\PineconePhp\VectorApi;
use Darcher\PineconePhp\VectorCollectionFactory;
use PHPUnit\Framework\TestCase;

class CompleteTest extends TestCase
{
    public string $indexName = 'test-index-name';
    public string $apiKey = 'apy-key';

    public function test_full_flow()
    {
        $indexApi = IndexApi::build($this->apiKey);
        $indexList = $indexApi->list();

        if (empty($indexList)) {
            $result = $indexApi->create($this->indexName,3);
            $this->assertTrue($result);
            return;
        }

        foreach ($indexList as $value) {
            $this->assertEquals($this->indexName, $value);
        }

        $vectorApi = VectorApi::build($this->apiKey,indexName: $this->indexName);
        $this->assertEquals(['namespaces','dimension','indexFullness','totalVectorCount'], array_keys($vectorApi->describe()));

        $response = $vectorApi->upsertCollection(VectorCollectionFactory::create([
            [1,[0.11,0.21,0.12]],
            [2,[0.12,0.21,0.12]],
            [3,[0.13,0.21,0.12]],
        ]));
        $this->assertEquals(["upsertedCount" => 3], $response);

        $response = $vectorApi->upsertOne(new Vector('4', [0.14,0.21,0.12]));
        $this->assertEquals(["upsertedCount" => 1], $response);

        $response = $vectorApi->fetchByIds(['1','2']);

        $this->assertEqualsCanonicalizing([
            'vectors' => [
                ['id' => '1', [0.11,0.21,0.12]],
                ['id' => '2', [0.12,0.21,0.12]],
            ],
            'namespace' => ''
        ], $response);
    }
}