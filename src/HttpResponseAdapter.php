<?php

namespace Darcher\PineconePhp;

use Psr\Http\Message\ResponseInterface;

class HttpResponseAdapter
{
    public static function adapt(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $data = json_decode((string) $response->getBody(), true);

        if ($statusCode >= 400) {
            // Handle error responses...
        }

        return $data;
    }
}