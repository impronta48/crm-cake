<?php

namespace App\Controller;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Http\Client\Exception\NetworkException;
use Cake\Core\Configure;

class WhatsappController extends AppController
{
    private string $wa_base_url = '';
    private string $wa_api_key = '';
    
    public function initialize(): void
    {
        parent::initialize();
        $this->wa_base_url = Configure::read('wa_base_url');
        $this->wa_api_key = Configure::read('wa_api_key');
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

    public function sendMessage()
    {
        $this->autoRender = false;

        $respData = [];
        
        // $user = $this->request->getQuery('user');
        // $message = $this->request->getQuery('message');

        $user = $this->request->getData('user');
        $message = $this->request->getData('message');

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

            $url = $this->getUrl('/b2rm/messages/send');
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
}