<?php

use Darcher\PineconePhp\IndexApi;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class IndexApiTest extends TestCase
{
    private const API_KEY = 'test-api-key';

    private IndexApi $client;
    private ClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->client = new IndexApi($this->httpClient, $this->requestFactory, self::API_KEY, '');
    }

    public function addExpectResultToResponse(MockObject|ResponseInterface $response, ?array $expectedResult, int $expectedCode): void {
        $response->expects($this->once())->method('getStatusCode')->willReturn($expectedCode);
        $response->expects($this->once())->method('getBody')->willReturn(json_encode($expectedResult));
    }

    public function test_create_index()
    {
        $request = $this->createMock(RequestInterface::class);
        $this->requestFactory->expects($this->once())->method('createRequest')->willReturn($request);
        $request->expects($this->atLeastOnce())->method('withHeader')->willReturn($request);
        $request->expects($this->atLeastOnce())->method('withBody')->willReturn($request);

        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects($this->once())->method('sendRequest')->with($request)->willReturn($response);

        $this->addExpectResultToResponse($response, null, 200);

        $result = $this->client->create(123);
        $this->assertEquals(true, $result);
    }
}