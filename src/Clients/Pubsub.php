<?php

declare(strict_types=1);

namespace VergilLai\LaravelGoeasy\Clients;

use Firebase\JWT\{
    JWT
};

class Pubsub extends AbstractClient
{
    public function publish(string $channel, string $message): array
    {
        $response = $this->client->post('v2/pubsub/publish', [
            'json' => [
                'appkey' => $this->appkey,
                'channel' => $channel,
                'content' => $message,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function makeAccessToken(string|int $id, array|string $channels, bool $read = true, $write = false): string
    {
        if (empty($this->config['secret_key'])) {
            throw new Exception('missing secret key.');
        }

        $ttl = min($this->config['access_token_ttl'] ?? 10800, 10800);

        $payload = [
            'id' => $id,                // 必须与connect GoEasy时传入的id一致
            'channel' => $channels,     // 授权的channel
            'w' => $write,              // 写权限，是否允许publish
            'r' => $read,               // 读权限，是否允许subscribe
            'exp' => time() + $ttl,     // 过期时间， 表示token在此之前有效，为了安全，GoEasy不接受有效时间超过3小时的access token
        ];

        return JWT::encode($payload, $this->config['secret_key'], 'HS256');
    }
}