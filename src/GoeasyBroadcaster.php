<?php

namespace VergilLai\LaravelGoeasy;

use Illuminate\Support\Collection;
use VergilLai\LaravelGoeasy\Clients\Pubsub;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class GoeasyBroadcaster extends Broadcaster
{

    public function __construct(
        protected Pubsub $client
    )
    {
    }

    public function auth($request)
    {
    }

    public function validAuthenticationResponse($request, $result)
    {
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $channels = $this->formatChannels($channels);

        $payload['event'] = $event;

        foreach ($channels as $channel) {
            if (str_starts_with($channel, 'private-')) {
                $channel = 'protected-' . substr($channel, 8);
            }
            $content = json_encode($payload, JSON_UNESCAPED_UNICODE);
            try {
                $this->client->publish($channel, $content);
            } catch (\Throwable) {
            }
        }
    }
}