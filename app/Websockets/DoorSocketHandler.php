<?php

namespace App\Websockets;

use Exception;
use Ratchet\ConnectionInterface;
use BeyondCode\LaravelWebSockets\Apps\App;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\ConnectionsOverCapacity;


class DoorSocketHandler implements MessageComponentInterface
{
    protected function validateAppKey(ConnectionInterface $conn)
    {
        $app_key = QueryParameters::create($conn->httpRequest)->get('appKey');
        $app = App::findByKey($app_key);

        if ($app == null) {

            $conn->app = App::findByKey(env('PUSHER_APP_KEY'));
            $conn->socketId = '4001.4001';

            $conn->close();

            throw new UnknownAppKey($app_key);
        }

        $conn->app = $app;
        return $this;
    }

    protected function validateCredential(ConnectionInterface $conn)
    {
        $device_id = QueryParameters::create($conn->httpRequest)->get('deviceId');
        $token = QueryParameters::create($conn->httpRequest)->get('token');

        if ($device_id == null || $token == null) {
            $conn->close();
        }



        return $this;
    }

    protected function generateSocketId(ConnectionInterface $conn)
    {
        $socket_id = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
        $conn->socketId = $socket_id;

        return $this;
    }

    protected function establishConnection(ConnectionInterface $conn)
    {
        $conn->send(json_encode([
            'event' => 'connection_established',
            'data' => json_encode([
                'socket_id' => $conn->socketId,
            ]),
        ]));

        DashboardLogger::connection($conn);
        StatisticsLogger::connection($conn);

        return $this;
    }


    public function onOpen(ConnectionInterface $conn)
    {
        $this->validateAppKey($conn)->generateSocketId($conn)->establishConnection($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
    }

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        $conn->send($msg->getPayload());
    }
}
