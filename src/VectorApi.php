<?php

namespace Darcher\PineconePhp;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Nyholm\Psr7\Stream;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class VectorApi
{
    private string $apiKey;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private string $baseUri;

    public static function build(string $apiKey, string $host = null, string $indexName = null, string $environment = 'us-west1-gcp')
    {
        if (!$host) {
            if (!$indexName) {
                throw new \Exception('Unable to create vector class, you must set host or indexName');
            }
            $indexApi = IndexApi::build($apiKey, $environment);
            $index = $indexApi->describe($indexName);
            $host = 'https://' . $index['status']['host'];
        }

        return new self(new Client(), new HttpFactory(), $apiKey, $host);
    }

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        string $apiKey,
        string $host
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->apiKey = $apiKey;
        $this->baseUri = $host;
    }

    public function describe()
    {
        $request = $this->requestFactory->createRequest('GET', $this->baseUri . '/describe_index_stats')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json; charset=utf-8');

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function queryByVector(array $vector, int $topK, string $namespace = '', bool $includeValues = false, bool $includeMetadata = false, $sparseVector = null, $filter = null) {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/query')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode([
            'top_k' => $topK,
            'namespace' => $namespace,
            'include_values' => $includeValues,
            'include_metadata' => $includeMetadata,
            'vector' => $vector,
            'sparseVector' => $sparseVector,
            'filter' => $filter,
        ])));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function queryById(string $id, int $topK, string $namespace = '', bool $includeValues = false, bool $includeMetadata = false, $sparseVector = null, $filter = null) {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/query')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode([
            'top_k' => $topK,
            'namespace' => $namespace,
            'include_values' => $includeValues,
            'include_metadata' => $includeMetadata,
            'id' => $id,
            'sparseVector' => $sparseVector,
            'filter' => $filter,
        ])));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function upsertCollection(VectorCollection $vectors)
    {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/vectors/upsert')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode($vectors)));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    public function upsertOne(Vector $vector)
    {
        return $this->upsertCollection(new VectorCollection([$vector]));
    }

    public function update(Vector $vector)
    {
        $request = $this->requestFactory->createRequest('POST', $this->baseUri . '/vectors/update')
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        $request = $request->withBody(Stream::create(json_encode($vector)));

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }

    /**
     * @param string[] $ids
     */
    public function fetchByIds(array $ids, string $namespace = null)
    {
        $queryParams = http_build_query(['namespace' => $namespace]) . '&ids=' .implode('&ids=',$ids);
        $request = $this->requestFactory->createRequest('GET', $this->baseUri . '/vectors/fetch?' . $queryParams)
            ->withHeader('Api-Key', $this->apiKey)
            ->withHeader('Accept', 'application/json');

        $response = $this->httpClient->sendRequest($request);

        return HttpResponseAdapter::adapt($response);
    }
}