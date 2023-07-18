<?php

namespace VergilLai\LaravelGoeasy\Clients;

use GuzzleHttp\Client;
use Exception;

abstract class AbstractClient
{
    protected Client $client;

    protected ?string $appkey;

    public function __construct(
        protected array $config
    ) {
        $this->appkey = !empty($this->config['rest_key']) ? $this->config['rest_key'] :
            (!empty($this->config['client_key']) ? $this->config['client_key'] : throw new Exception('appkey is required'));

        $this->client = new Client([
            'base_uri' => $this->config['host'],
        ]);
    }

    public function otp(): string
    {
        $key = $this->config['secret_key'];
        [$t1, $t2] = explode(' ', microtime());
        $text = (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        $text = '000'.$text;
        return openssl_encrypt($text, 'AES-128-ECB', $key, 2, '');
    }

}