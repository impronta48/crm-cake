<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Chat\ChatServerRatchet;

class WebSocketServerCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        echo "Starting...\n";
        try {
            $port = ChatServerRatchet::getInstance()->serverPort;
            echo "Server port {$port} \n";

            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        ChatServerRatchet::getInstance()
                    )
                ),
                // 5001
                $port
            );

            echo "WebSocket server started\n";

            $server->run();
        } catch(\Exception $e) {
            echo "Error\n";
            echo $e;
        }
            
        return static::CODE_SUCCESS;
    }
}