<?php

namespace App\Socket;

use App\Events\Event;
use Ratchet\ConnectionInterface;
use React\Socket\ConnectorInterface;

class Broadcast{
    protected $clients;
    protected $event;

    public function __construct(Event $event,array $clients) {
        $this->event = $event;
        $this->clients = $clients;
    }

    public function toAll()
    {
       foreach($this->clients as $client){
            $client->send($this->event);
       }
    }
    public function to(ConnectionInterface $client)
    {
    
            $client->send($this->event);
       
    }

    public function toAllExcept(ConnectionInterface $clientExclude)
    {   
        foreach($this->clients as $client){
            if($client !== $clientExclude){
                $client->send($this->event);
            }
           
       }
    }
}