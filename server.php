<?php

use App\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require_once 'vendor/autoload.php';

// $server = IoServer::factory(
//     new HttpServer(
//         new WsServer(
//             new Chat()
//         )
//         ),
//         8080
// );
// $server->run()


$chatServer =  new Chat();
$port = 1234;
$ip='0.0.0.0';

$wsServer = new WsServer($chatServer);
// $wsServer->disableVersion(0);
    	
$http = new HttpServer($wsServer);
$server = IoServer::factory($http, $port, $ip);

$server->run();
?>
