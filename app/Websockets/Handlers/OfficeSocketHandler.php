<?php

namespace App\Websockets\Handlers;

use App\Models\Door;
use App\Websockets\Exceptions\InvalidCredentials;
use Exception;
use Ratchet\ConnectionInterface;
use BeyondCode\LaravelWebSockets\Apps\App;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;


class OfficeSocketHandler implements MessageComponentInterface
{
    protected function validateAppKey(ConnectionInterface $conn)
    {
        $app_key = QueryParameters::create($conn->httpRequest)->get('app');
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

    protected function generateSocketId(ConnectionInterface $conn)
    {
        $socket_id = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
        $conn->socketId = $socket_id;

        return $this;
    }

    protected function validateCredential(ConnectionInterface $conn)
    {
        $header = $conn->httpRequest->getHeaders();

        $device_id = $header['Device-Id'][0];
        $token = $header['Token'][0];

        $device = Door::where('device_id', $device_id)->where('token', $token)->first();

        if ($device == null) {
            $conn->close();
            throw new InvalidCredentials($device_id, $token);
        } else {
        }

        $device->socket_id = $conn->socketId;
        $device->is_lock = $header['Is-Lock'][0];
        $status = $device->save();

        if ($status) {
            dump('here');
        }

        return $this;
    }

    protected function establishConnection(ConnectionInterface $conn)
    {
        $conn->send('success');

        DashboardLogger::connection($conn);
        StatisticsLogger::connection($conn);

        return $this;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->validateAppKey($conn)->generateSocketId($conn)->establishConnection($conn);
    }

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        dump('message : ' . $msg->getPayload());
    }

    public function onClose(ConnectionInterface $conn)
    {
        dump('close');
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        dump('error');
    }
}
