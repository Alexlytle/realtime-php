<?php
namespace App;

use App\Events\Message;
use App\Events\Users;
use App\Events\UserJoined;
use Ratchet\ConnectionInterface;


    trait ChatEventsTrait{
        public function handleMessage(ConnectionInterface $connection,$payload)
        {
            $user = $this->users[$connection->resourceId];
            $message = $payload->data;
            $this->broadcast(new Message($user,$message))->toAllExcept($connection);
        }
        protected function handleJoined(ConnectionInterface $connection,$payload)
        {
            $user = $payload->data->user;
            $this->users[$connection->resourceId] = $payload->data->user;
            // 
            // var_dump($payload);

      
          $this->broadcast(new Users($this->users))->to($connection);
          $this->broadcast(new UserJoined($user))->toAllExcept($connection);
            
         

        }
    }
?>