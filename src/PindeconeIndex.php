<?php

namespace Darcher\PineconePhp;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PindeconeIndex
{
    private string $environment;
    private string $apiKey;

    public function __construct(string $apiKey, string $environment = 'us-east1-gcp')
    {
        $this->apiKey = $apiKey;
        $this->environment = $environment;
    }

    public function createIndex(
        string $name,
        int $dimension = 1536,
        string $metric = 'cosine',
        $metadata_config = null,
        $pods = 1,
        $replicas = 1,
        $pod_type = 'p1.x1'
    ) {
        $client = $this->getClient();

        try {
            $response = $client->post('/databases', [
                'json' => [
                    'name' => $name,
                    'dimension' => $dimension,
                    'metric' => $metric,
                    'pods' => $pods,
                    'replicas' => $replicas,
                    'pod_type' => $pod_type,
                    'metadata_config' => $metadata_config,
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return true;
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                if ($response->getStatusCode() == 400) {
                    throw new Exception("Bad request. Encountered when request exceeds quota or an invalid index name.");
                }
                if ($response->getStatusCode() == 409) {
                    throw new Exception("Index of given name already exists.");
                }
                if ($response->getStatusCode() == 500) {
                    throw new Exception("Internal error. Can be caused by invalid parameters.");
                }
                throw new Exception("An error occurred: " . $e->getMessage());
            }

            throw new Exception("Unexpected error occurred");
        }

        return false;
    }

    public function getClient(): Client
    {
        $client = new Client([
            'base_uri' => 'https://controller.' . $this->environment . '.pinecone.io',
            'timeout' => 30,
            'headers' => [
                'Api-Key' => $this->apiKey,
                'Accept' => 'application/json; charset=utf-8', //create index text/plain
                'Content-Type' => 'application/json'
            ]
        ]);
        return $client;
    }
}