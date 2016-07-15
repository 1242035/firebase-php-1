<?php

namespace Kreait\Firebase;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Kreait\Firebase\Database\Query;
use Kreait\Firebase\Database\Reference;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Http\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Database
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(string $location)
    {
        return $this->sendAndReturnValue(new Request('GET', $location));
    }

    public function query(string $location): Query
    {
        return $this->with($location)->query();
    }

    public function with(string $location): Reference
    {
        return new Reference($location, $this);
    }

    public function set(string $location, $value)
    {
        return $this->sendAndReturnValue(new Request('PUT', $location, [], json_encode($value)));
    }

    /**
     * Adds the given value as a child to the given location and returns its name.
     *
     * @param string $location
     * @param mixed $value
     *
     * @return string
     */
    public function push(string $location, $value): string
    {
        $result = $this->sendAndReturnValue(new Request('POST', $location, [], json_encode($value)));

        return $result['name'];
    }

    public function update(string $location, array $values)
    {
        return $this->sendAndReturnValue(new Request('PATCH', $location, [], json_encode($values)));
    }

    public function delete(string $location)
    {
        $this->send(new Request('DELETE', $location));
    }

    private function send(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (RequestException $e) {
            throw DatabaseException::fromGuzzleException($e);
        }
    }

    private function sendAndReturnValue(RequestInterface $request)
    {
        return json_decode((string) $this->send($request)->getBody(), true);
    }
}
