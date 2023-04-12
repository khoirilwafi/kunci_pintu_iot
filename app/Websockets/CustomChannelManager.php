<?php

namespace App\Websockets;

use App\Models\Socket;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PresenceChannel;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ratchet\ConnectionInterface;

class CustomChannelManager implements ChannelManager
{
    /** @var string */
    protected $appId;

    /** @var array */
    protected $channels = [];

    public function findOrCreate(string $appId, string $channelName): Channel
    {
        if (!isset($this->channels[$appId][$channelName])) {
            $channelClass = $this->determineChannelClass($channelName);

            $this->channels[$appId][$channelName] = new $channelClass($channelName);

            // prepare data
            $data['app_id'] = $appId;

            if (Str::startsWith($channelName, 'private-')) {
                $data['type'] = 'private';
                $data['channel'] = substr($channelName, 8);
            } else if (Str::startsWith($channelName, 'presence-')) {
                $data['type'] = 'presence';
                $data['channel'] = substr($channelName, 9);
            } else {
                $data['type'] = 'public';
                $data['channel'] = $channelName;
            }

            // insert to database
            Socket::updateOrCreate(['channel' => $data['channel']], $data);
        }

        return $this->channels[$appId][$channelName];
    }

    public function find(string $appId, string $channelName): ?Channel
    {
        return $this->channels[$appId][$channelName] ?? null;
    }

    protected function determineChannelClass(string $channelName): string
    {
        if (Str::startsWith($channelName, 'private-')) {
            return PrivateChannel::class;
        }

        if (Str::startsWith($channelName, 'presence-')) {
            return PresenceChannel::class;
        }

        return Channel::class;
    }

    public function getChannels(string $appId): array
    {
        return $this->channels[$appId] ?? [];
    }

    public function getConnectionCount(string $appId): int
    {
        return collect($this->getChannels($appId))
            ->flatMap(function (Channel $channel) {
                return collect($channel->getSubscribedConnections())->pluck('socketId');
            })
            ->unique()
            ->count();
    }

    public function removeFromAllChannels(ConnectionInterface $connection)
    {
        if (!isset($connection->app)) {
            return;
        }

        /*
         * Remove the connection from all channels.
         */
        collect(Arr::get($this->channels, $connection->app->id, []))->each->unsubscribe($connection);


        /*
         * Unset all channels that have no connections so we don't leak memory.
         */
        collect(Arr::get($this->channels, $connection->app->id, []))
            ->reject->hasConnections()
            ->each(function (Channel $channel, string $channelName) use ($connection) {
                unset($this->channels[$connection->app->id][$channelName]);

                // prepare data
                $channel = '';

                if (Str::startsWith($channelName, 'private-')) {
                    $channel = substr($channelName, 8);
                } else if (Str::startsWith($channelName, 'presence-')) {
                    $channel = substr($channelName, 9);
                } else {
                    $channel = $channelName;
                }

                // delete data
                $socket = Socket::where('channel', $channel)->first();
                if ($socket) $socket->delete();
            });

        if (count(Arr::get($this->channels, $connection->app->id, [])) === 0) {
            unset($this->channels[$connection->app->id]);
        }
    }
}
