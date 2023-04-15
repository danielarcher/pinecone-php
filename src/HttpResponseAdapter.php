<?php

namespace Darcher\PineconePhp;

use Psr\Http\Message\ResponseInterface;

class HttpResponseAdapter
{
    public static function adapt(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $data = json_decode((string) $response->getBody(), true);

        if ($statusCode >= 400) {

        }

        if (in_array($statusCode, [200,201,202]) && empty($data) && !is_array($data)) {
            return true;
        }

        return $data;
    }
}