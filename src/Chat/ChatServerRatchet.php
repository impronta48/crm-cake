<?php
// src/Chat/ChatServer.php

namespace App\Chat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Cake\Core\Configure;

class ChatServerRatchet implements MessageComponentInterface
{
    private static $instance = null;
    protected $clients;

    public $serverPort;
    public $vueApiKey;
    public $cakeApiKey;

    private function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->serverPort = Configure::read('WebSocket.port');
        $this->vueApiKey = Configure::read('WebSocket.vue_api_key');
        $this->cakeApiKey = Configure::read('WebSocket.cake_api_key');
    }

    public static function getInstance()
    {
       if(self::$instance == null)
       {   
          $c = __CLASS__;
          self::$instance = new $c;
       }
       
       return self::$instance;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";

        $path = $conn->httpRequest->getUri()->getPath();
        $query = $conn->httpRequest->getUri()->getQuery();
        $queries = array();
        parse_str($query, $queries);
        $token = $queries['token'];
        // echo "token: {$token}\n";
        // echo "this->vueApiKey: {$this->vueApiKey}\n";
        // echo "this->cakeApiKey: {$this->cakeApiKey}\n";
        if ($token != null) {
            if ($token === $this->vueApiKey) {
                $socket_name = $this->vueApiKey;
                $this->clients->attach($conn, $socket_name);
            }
            else if ($token === $this->cakeApiKey) {
                $socket_name = $this->cakeApiKey;
                $this->clients->attach($conn, $socket_name);
            }
            else {
                echo "Client unknown. Close it!\n";
                $conn->close();
            }
        }
        
        // echo "http path: ({$path})\n";
        // echo "http query: ({$query})\n";
        
        echo "Clients count: ({$this->clients->count()})\n";
        
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // echo "Message: ({$msg})\n";
        foreach ($this->clients as $client) {
            if ($from === $client) {
                if ($this->clients->getInfo() != $this->cakeApiKey) {
                    echo "From not allowed";
                    return;
                }
            }
        }

        foreach ($this->clients as $client) {
            if ($this->clients->getInfo() === $this->vueApiKey) {
                // echo "Sending message: ({$msg})\n";
                $client->send($msg);
            }        
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
        echo "Clients count: ({$this->clients->count()})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}