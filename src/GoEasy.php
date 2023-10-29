<?php

namespace VergilLai\LaravelGoeasy;

use VergilLai\LaravelGoeasy\Clients\IM;
use VergilLai\LaravelGoeasy\Clients\Pubsub;

class GoEasy
{
    protected string $host;

    protected string $appkey;

    protected Pubsub $pubsub;

    protected IM $im;

    public function __construct(
        protected array $config
    ) {
        $this->appkey = !empty($this->config['rest_key']) ? $this->config['rest_key'] :
            (!empty($this->config['common_key']) ? $this->config['common_key'] : throw new Exception('appkey is required'));

        if (filter_var($this->config['host'], FILTER_VALIDATE_URL) === false) {
            throw new \Exception('invalid host');
        }
        $this->host = rtrim($this->config['host'], '/');
    }

    public function otp(): string
    {
        $key = $this->config['secret_key'];
        [$t1, $t2] = explode(' ', microtime());
        $text = (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        $text = '000'.$text;
        return openssl_encrypt($text, 'AES-128-ECB', $key, 2, '');
    }

    public function pubsub(): Pubsub
    {
        return $this->pubsub ??= new Pubsub($this->host, $this->appkey, $this->config['secret_key']);
    }

    public function im(): IM
    {
        return $this->im ??= new IM($this->host, $this->appkey, $this->config['secret_key']);
    }
}
