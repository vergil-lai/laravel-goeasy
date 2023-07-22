<?php

namespace VergilLai\LaravelGoeasy\Clients;

use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class IM extends AbstractClient
{
    /**
     * @see https://docs.goeasy.io/2.x/common/security/authorization/im
     * @param  int|string  $id
     * @param  array|string  $data
     * @param  bool  $read
     * @param $write
     * @return string
     */
    public function makeAccessToken(int|string $id, array|string $data, bool $read = true, $write = true): string
    {
        if (empty($this->secretKey)) {
            throw new Exception('missing secret key.');
        }

        $ttl = min($this->config['access_token_ttl'] ?? 10800, 10800);

        $payload = [
            'id' => $id,                // 必须与connect GoEasy时传入的id一致
            'to' => $data,              // 群id数组或私聊对象id
            'w' => $write,              // 写权限，是否允许发送
            'r' => $read,               // 读权限，是否允许订阅群消息
            'exp' => time() + $ttl,     // 过期时间， 表示token在此之前有效，为了安全，GoEasy不接受有效时间超过3小时的access token
        ];

        return JWT::encode($payload,$this->secretKey, 'HS256');
    }
}