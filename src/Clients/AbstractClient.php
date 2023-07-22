<?php

namespace VergilLai\LaravelGoeasy\Clients;

use GuzzleHttp\Client;
use Exception;

abstract class AbstractClient
{
    protected Client $client;

    public function __construct(
        protected string $host,
        protected string $appkey,
        protected string $secretKey,
    )
    {
    }

    abstract public function makeAccessToken(string|int $id, array|string $data, bool $read = true, $write = false): string;
}