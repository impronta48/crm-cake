<?php

namespace App\Controller;

use Cake\Log\Log;
use Cake\Core\Configure;
use App\Chat\WhatsappService;

class WhatsappController extends AppController
{
    private string $ws_base_url = '';
    private string $ws_cake_api_key = '';
    
    public function initialize(): void
    {
        parent::initialize();
     
        $this->ws_base_url = Configure::read('WebSocket.base_url') . ":" . Configure::read('WebSocket.port');
        $this->ws_cake_api_key = Configure::read('WebSocket.cake_api_key');
    }

    public function sessions()
    {
        $this->autoRender = false;

        $respData = WhatsappService::getInstance()->sessions();

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function addSession($id)
    {
        $this->autoRender = false;

        $respData = WhatsappService::getInstance()->addSession($id);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function conversation() {
        $this->autoRender = false;

        $sessionId = $this->request->getQuery('session');
        $user = $this->request->getQuery('user');

        $respData = WhatsappService::getInstance()->conversation($sessionId, $user);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function sendMessage()
    {
        $this->autoRender = false;

        $respData = [];
        
        $session = $this->request->getData('session');
        $user = $this->request->getData('user');
        $message = $this->request->getData('message');

        $respData = WhatsappService::getInstance()->sendMessage($session, $user, $message);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function receive()
    {
        $this->autoRender = false;

        $respData = [
            'success' => true,
            'data' => [] 
        ];

        $data = $this->request->getData();
        if($data != null) {
            
            // $msg = json_encode($data);
            // Log::info("WA receive: " . $msg);
          
            $ws_connection_url = $this->ws_base_url . '/?token=' . $this->ws_cake_api_key;

            \Ratchet\Client\connect($ws_connection_url)->then(function($conn) {

                $data = $this->request->getData();
                if($data != null) {                
                    $msg = json_encode($data);
                    // Log::info("WA send: " . $msg);
                    $conn->send($msg);
                }
                $conn->close();

            }, function ($e) {
                Log::error("WebSocket Could not connect: {$e->getMessage()}");
            });
        }

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }
}