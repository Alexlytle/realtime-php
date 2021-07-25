<?php

namespace App;

use Exception;
use App\ChatEventsTrait;
use App\Events\UserLeft;
use App\Socket\SocketAbstract;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class Chat extends SocketAbstract implements MessageComponentInterface
{
    use ChatEventsTrait;
    protected $clients;

    protected $users;

    function onOpen(ConnectionInterface $connection)
    {
        $this->clients[$connection->resourceId] = $connection;

        // var_dump(count($this->clients));
    }
    public function onMessage(ConnectionInterface $connection, $message )
    {
        // var_dump($message);
        $payload = json_decode($message);
        $this->users[$connection->resourceId] = $payload->data->user;
        // var_dump($this->users);

        if(method_exists($this, $method = 'handle' . ucfirst($payload->event))){
            $this->{$method}($connection,$payload);
        }
    }

    function onClose(ConnectionInterface $connection)
    {
      

        if(!isset(  $this->users[$connection->resourceId] )){
            return;
        }
        $user = $this->users[$connection->resourceId];
        $this->broadcast(new UserLeft($user))->toAll($user);
        unset($this->clients[$connection->resourceId],$this->users[$connection->resourceId]);
    }

 
    function onError(ConnectionInterface $connection, \Exception $e){

    }

   
}