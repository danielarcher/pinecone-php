<?php

namespace Darcher\PineconePhp\Tests;

use Darcher\PineconePhp\PinecodeClientGPT;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PinecodeClientTest extends TestCase
{
    private const API_KEY = 'test-api-key';

    private PinecodeClientGPT $client;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);

        $this->client = new PinecodeClientGPT($this->httpClient, $this->requestFactory, self::API_KEY);
    }

    public function testCreateCollection(): void
    {
        $collectionName = 'example_collection';
        $dimensions = 128;

        $request = $this->mockRequestWithAuthorization();

        $request->expects($this->once())
            ->method('withBody')
            //->with(json_encode(['name' => $collectionName, 'dimensions' => $dimensions]))
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->createCollection($collectionName, $dimensions);
        $this->assertEquals($expectedResult, $result);
    }

    public function testInsertVector(): void
    {
        $collectionName = 'example_collection';
        $vectorId = 'vector1';
        $vector = [0.1, 0.2, 0.3, 0.4];

        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->with('POST', PinecodeClientGPT::API_BASE_URL . '/collections/example_collection/vectors')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('withBody')
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->insertVector($collectionName, $vectorId, $vector);
        $this->assertEquals($expectedResult, $result);
    }

    public function testSearch(): void
    {
        $collectionName = 'example_collection';
        $queryVector = [0.1, 0.2, 0.3, 0.4];
        $topK = 5;

        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->with('POST', PinecodeClientGPT::API_BASE_URL . '/collections/example_collection/search')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('withBody')
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->search($collectionName, $queryVector, $topK);
        $this->assertEquals($expectedResult, $result);
    }

    public function testUpdateVector(): void
    {
        $collectionName = 'example_collection';
        $vectorId = 'vector1';
        $newVector = [0.5, 0.6, 0.7, 0.8];

        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->with('PUT', PinecodeClientGPT::API_BASE_URL . '/collections/example_collection/vectors/vector1')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);

        $request->expects($this->once())
            ->method('withBody')
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->updateVector($collectionName, $vectorId, $newVector);
        $this->assertEquals($expectedResult, $result);
    }

    public function testDeleteVector(): void
    {
        $collectionName = 'example_collection';
        $vectorId = 'vector1';

        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->deleteVector($collectionName, $vectorId);
        $this->assertEquals($expectedResult, $result);
    }

    public function testDeleteCollection(): void
    {
        $collectionName = 'example_collection';

        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);

        $response = $this->mockResponse($request);

        $expectedResult = ['result' => 'success'];
        $this->addExpectResultToResponse($response, $expectedResult);

        $result = $this->client->deleteCollection($collectionName);
        $this->assertEquals($expectedResult, $result);
    }

    public function mockRequestWithAuthorization(): MockObject|RequestInterface
    {
        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())
            ->method('createRequest')
            ->with('POST', PinecodeClientGPT::API_BASE_URL . '/collections')
            ->willReturn($request);

        $request->expects($this->atLeastOnce())
            ->method('withHeader')
            ->willReturn($request);
        return $request;
    }

    public function mockResponse(RequestInterface|MockObject $request): ResponseInterface|MockObject {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);
        return $response;
    }

    public function addExpectResultToResponse(MockObject|ResponseInterface $response, array $expectedResult): void {
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn(json_encode($expectedResult));
    }
}
