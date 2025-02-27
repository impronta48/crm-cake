<?php

namespace App\Controller;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Http\Client\Exception\NetworkException;
use Cake\Core\Configure;
use Exception;

class WhatsappController extends AppController
{
    private string $wa_base_url = '';
    private string $wa_api_key = '';

    private string $ws_base_url = '';
    private string $ws_cake_api_key = '';
    
    public function initialize(): void
    {
        parent::initialize();

        $this->wa_base_url = Configure::read('WhatsApp.base_url') . ":" . Configure::read('WhatsApp.port');
        $this->wa_api_key = Configure::read('WhatsApp.api_key');

     
        $this->ws_base_url = Configure::read('WebSocket.base_url') . ":" . Configure::read('WebSocket.port');
        $this->ws_cake_api_key = Configure::read('WebSocket.cake_api_key');
    }

    private function getUrl(string $uri) {
        return $this->wa_base_url . $uri;
    }

    private function getHeaders() {
        return ['X-API-Key' => $this->wa_api_key,
                'decode_content' => 'json'];
    }

    private function getErrorResponse($error) {
        $respData = ['error' => $error];
        return $respData;
    }

    public function listSession()
    {
        $this->autoRender = false;

        $respData = [];
        
        try {
            $http = new Client();

            $url = $this->getUrl('/sessions');
            $headers = $this->getHeaders();
            
            $response = $http->get($url,[], ['headers' => $headers]);
            
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);

                Log::info("respData: " . json_encode($respData));
            } else {
                $respData = $this->getErrorResponse('Error in List Session');

                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);

                Log::info("respData: " . json_encode($respData));
            }
        }
        catch (NetworkException $e) {
            Log::info("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Exception in List Session');
        }

        // $this->set(compact('data'));
        // $this->viewBuilder()->setOption('serialize', ['data']);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function addSession($id)
    {
        $this->autoRender = false;

        $respData = [];
        
        if ($id === null || trim($id) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
        }

        try {
            $http = new Client();

            $url = $this->getUrl('/sessions/add');
            $headers = $this->getHeaders();

            $data = [
                        "sessionId" => $id
                    ];

            $response = $http->post($url, json_encode($data), ['type' => 'json', 'headers' => $headers]);

            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);
            } else {
                $respData = $this->getErrorResponse('Error in Add Session');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse(json_encode($e));
        }

        // $this->set(compact('data'));
        // $this->viewBuilder()->setOption('serialize', ['data']);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function conversation() {
        $this->autoRender = false;

        $respData = [];
        
        try {
            $http = new Client();

            $sessionId = $this->request->getQuery('session');
            $user = $this->request->getQuery('user');
            $jid = $user . '@s.whatsapp.net';

            // $sessionId = 'b2rm';
            // $jid = '393481506912@s.whatsapp.net';
            // $url = $this->getUrl('/chats' . '/:' . $jid);
            $url = $this->getUrl('/'.$sessionId.'/chats' . '/' . $jid);
            Log::info("url: " . $url);

            $headers = $this->getHeaders();
            
            $response = $http->get($url,[], ['headers' => $headers]);
            
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);

                Log::info("respData: " . json_encode($respData));
            } else {
                $respData = $this->getErrorResponse('Error in List Session');

                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);

                Log::info("respData: " . json_encode($respData));
            }
        }
        catch (NetworkException $e) {
            Log::info("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Exception in List Session');
        }

        // $this->set(compact('data'));
        // $this->viewBuilder()->setOption('serialize', ['data']);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function sendMessage()
    {
        $this->autoRender = false;

        $respData = [];
        
        // $user = $this->request->getQuery('user');
        // $message = $this->request->getQuery('message');

        $session = $this->request->getData('session');
        $user = $this->request->getData('user');
        $message = $this->request->getData('message');

        if ($session === null || trim($session) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
        }

        if ($user === null || trim($user) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
        }

        if ($message === null || trim($message) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
        }

        try {
            $http = new Client();

            $url = $this->getUrl('/'.$session.'/messages/send');
            $headers = $this->getHeaders();

            $data = [
                        "jid" => $user . "@s.whatsapp.net",  //numero deve iniziare con 39
                        "type" => "number",
                        "message" => [
                            "text" => $message
                        ]
                    ];

            $response = $http->post($url, json_encode($data), ['type' => 'json', 'headers' => $headers]);
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);
            } else {
                $respData = $this->getErrorResponse('Error in Add Session');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Error in Add Session');
        }

        // $this->set(compact('data'));
        // $this->viewBuilder()->setOption('serialize', ['data']);

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
            
            $msg = json_encode($data);
            // Log::info("WA receive: " . $msg);
          
            $ws_connection_url = $this->ws_base_url . '/?token=' . $this->ws_cake_api_key;
            \Ratchet\Client\connect($ws_connection_url)->then(function($conn) {

                $data = $this->request->getData();
                if($data != null) {                
                    $msg = json_encode($data);

                    // Log::info("WA send: " . $msg);
                    $conn->send($msg);
                }
                else {
                    Log::info("WA send Hello");
                    $conn->send("Hello!");
                }
                $conn->close();

                // $conn->on('message', function($inner_msg) use ($conn) {
                //     Log::info("Ws receive: " . $inner_msg);
                //     $conn->close();
                // }); 

            }, function ($e) {
                Log::error("Could not connect: {$e->getMessage()}");
            });
        }
        // $this->set(compact('data'));
        // $this->viewBuilder()->setOption('serialize', ['data']);

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }
}