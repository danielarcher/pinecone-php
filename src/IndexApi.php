<?php

namespace Darcher\PineconePhp;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Nyholm\Psr7\Stream;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class IndexApi
{
    private string $environment;
    private string $apiKey;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private string $baseUri;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        string $apiKey,
        string $environment
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->apiKey = $apiKey;
        $this->environment = $environment;
        $this->baseUri = 'https://controller.' . $this->environment . '.pinecone.io';
    }

    public static function build(string $apiKey, string $environment = 'us-west1-gcp')
    {
        return new self(new Client(), new HttpFactory(), $apiKey, $environment);
    }

    public function list()
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUri . '/databases')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json; charset=utf-8');

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function describe(string $indexName)
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUri . '/databases/' . $indexName)
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json; charset=utf-8');

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function delete(string $indexName)
    {
        $request = $this->requestFactory->createRequest('DELETE', $this->baseUri . '/databases/' . $indexName)
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'text/plain');

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function configure($indexName, int $replicas, PodType $podType)
    {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/databases/' . $indexName)
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'text/plain')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode([
            'replicas' => $replicas,
            'pod_type' => $podType,
        ])));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function create(
        string $name,
        int $dimension = 1536,
        Metric $metric = Metric::COSINE,
        ?array $metadataConfig = null,
        int $pods = 1,
        int $replicas = 1,
        PodType $podType = PodType::P1_X1
    ) {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/databases')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'text/plain')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode([
            'name' => $name,
            'dimension' => $dimension,
            'metric' => $metric,
            'pods' => $pods,
            'replicas' => $replicas,
            'pod_type' => $podType,
            'metadata_config' => $metadataConfig,
        ])));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }
}