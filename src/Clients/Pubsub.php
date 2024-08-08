<?php

declare(strict_types=1);

namespace VergilLai\LaravelGoeasy\Clients;

use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class Pubsub extends AbstractClient
{
    /**
     * Rest接口服务端发送
     * @see https://docs.goeasy.io/2.x/pubsub/message/rest-publish
     * @param  array $params
     * @return bool
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function publish(array $params): array
    {
        $response = $this->client->post('v2/pubsub/publish', [
            'json' => [
                'appkey' => $this->appkey,
                ...$params,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 历史消息
     * 获取某个channel上发送的历史消息，您可以根据具体的参数来指定返回符合您期望的历史消息。
     * @see https://docs.goeasy.io/2.x/pubsub/advanced/messagehistory
     * @param  string  $channel 要查询的channel
     * @param  int  $start 要查询时间范围起始时间的毫秒数，默认为0，查询范围为从该channel上产生的第一条消息开始
     * @param  int|null  $end 要查询时间范围起始时间的毫秒数，默认为当前时间，查询范围为该channel上截止当前此刻的所有消息
     * @param  int  $limit 默认10，最大值为30
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function history(string $channel, int $start = 0, ?int $end = null, int $limit = 10): array
    {
        $limit = min($limit, 30);

        $response = Http::retry(3, 100)->get($this->host . '/v2/pubsub/history',[
            'appkey' => $this->appkey,
            'channel' => $channel,
            'start' => $start,
            'end' => $end,
            'limit' => $limit,
        ])->throw();

        $res = $response->json('content.messages');

        return collect($res)->map(fn ($item) => [
            'content' => json_decode($item['content'], true),
            'time' => $item['time'],
        ])->toArray();
    }

    /**
     * 查询在线客户端列表
     * @see https://docs.goeasy.io/2.x/pubsub/presence/herenow-rest
     * @param  string  $channel 必须项，可以包含一个或多个channel
     * @param  bool  $includeUsers 可选项，是否返回用户列表，默认false
     * @param  bool  $distinct 可选项，相同userId的客户端，列表中只保留一个，默认false
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function herenow(string $channel, bool $includeUsers = false, bool $distinct = false): array
    {
        $response = Http::retry(3, 100)->get($this->host . '/v2/pubsub/herenow',[
            'appkey' => $this->appkey,
            'channel' => $channel,
            'includeUsers' => $includeUsers ? 'true' : 'false',
            'distinct' => $distinct ? 'true' : 'false',
        ])->throw();

        return $response->json('content');
    }

    /**
     * @see https://docs.goeasy.io/2.x/common/security/authorization/pubsub
     * @param  string|int  $id
     * @param  array|string  $data
     * @param  bool  $read
     * @param $write
     * @return string
     */
    public function makeAccessToken(string|int $id, array|string $data, bool $read = true, $write = false): string
    {
        if (empty($this->secretKey)) {
            throw new Exception('missing secret key.');
        }

        $ttl = min($this->config['access_token_ttl'] ?? 10800, 10800);

        $payload = [
            'id' => $id,                // 必须与connect GoEasy时传入的id一致
            'channel' => $data,         // 授权的channel
            'w' => $write,              // 写权限，是否允许publish
            'r' => $read,               // 读权限，是否允许subscribe
            'exp' => time() + $ttl,     // 过期时间， 表示token在此之前有效，为了安全，GoEasy不接受有效时间超过3小时的access token
        ];

        return JWT::encode($payload,$this->secretKey, 'HS256');
    }
}
