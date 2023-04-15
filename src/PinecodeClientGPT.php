<?php

namespace Darcher\PineconePhp;

use Nyholm\Psr7\Stream;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class PinecodeClientGPT implements PinecodeApiClientInterface
{
    public const API_BASE_URL = 'https://api.pinecode.com';

    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private string $apiKey;

    public function __construct(ClientInterface $httpClient, RequestFactoryInterface $requestFactory, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->apiKey = $apiKey;
    }

    public function createCollection(string $collectionName, int $dimensions): array
    {
        $request = $this->requestFactory->createRequest('POST', self::API_BASE_URL . '/collections')
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey)
            ->withHeader('Content-Type', 'application/json');

        $body = json_encode(['name' => $collectionName, 'dimensions' => $dimensions]);
        $request = $request->withBody(Stream::create($body));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function insertVector(string $collectionName, string $vectorId, array $vector): array
    {
        $request = $this->requestFactory->createRequest('POST', self::API_BASE_URL . "/collections/{$collectionName}/vectors")
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey)
            ->withHeader('Content-Type', 'application/json');

        $body = json_encode(['id' => $vectorId, 'vector' => $vector]);
        $stream = Stream::create($body);
        $request = $request->withBody($stream);

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function search(string $collectionName, array $queryVector, int $topK): array
    {
        $request = $this->requestFactory->createRequest('POST', self::API_BASE_URL . "/collections/{$collectionName}/search")
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey)
            ->withHeader('Content-Type', 'application/json');

        $body = json_encode(['vector' => $queryVector, 'top_k' => $topK]);
        $stream = Stream::create($body);
        $request = $request->withBody($stream);

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function updateVector(string $collectionName, string $vectorId, array $newVector): array
    {
        $request = $this->requestFactory->createRequest('PUT', self::API_BASE_URL . "/collections/{$collectionName}/vectors/{$vectorId}")
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey)
            ->withHeader('Content-Type', 'application/json');

        $body = json_encode(['vector' => $newVector]);
        $stream = Stream::create($body);
        $request = $request->withBody($stream);

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function deleteVector(string $collectionName, string $vectorId): array
    {
        $request = $this->requestFactory->createRequest('DELETE', self::API_BASE_URL . "/collections/{$collectionName}/vectors/{$vectorId}")
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey);

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function deleteCollection(string $collectionName): array
    {
        $request = $this->requestFactory->createRequest('DELETE', self::API_BASE_URL . "/collections/{$collectionName}")
            ->withHeader('Authorization', 'Bearer ' . $this->apiKey);

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }
}
