<?php

namespace App\Chat;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Http\Client\Exception\NetworkException;
use Cake\Core\Configure;

use Cake\ORM\TableRegistry;

class WhatsappService 
{
    private static $instance = null;
    
    private string $phone_regex = '~[^\d]{0,7}(00)?(\d{1,2})?[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~';

    private string $wa_base_url = '';
    private string $wa_api_key = '';
    
    private function __construct()
    {
        $this->wa_base_url = Configure::read('WhatsApp.base_url') . ":" . Configure::read('WhatsApp.port');
        $this->wa_api_key = Configure::read('WhatsApp.api_key');
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

    public function getUrl(string $uri) {
        return $this->wa_base_url . $uri;
    }

    public function getHeaders() {
        return ['X-API-Key' => $this->wa_api_key,
                'decode_content' => 'json'];
    }

    private function getErrorResponse($error) {
        $respData = [
            'success' => false,
            'error' => $error
        ];
        return $respData;
    }

    public function sessions()
    { 
        $respData = ['success' => true];  
        try {
            $http = new Client();

            $url = $this->getUrl('/sessions');
            $headers = $this->getHeaders();
            
            $response = $http->get($url,[], ['headers' => $headers]);
            
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = [
                    'success' => true,
                    'data' => json_decode($body, true)
                ];  
            } else {
                $body = $response->getBody()->getContents();
                $respData = [
                    'success' => false,
                    'data' => json_decode($body, true)
                ];  
            }
        }
        catch (NetworkException $e) {
            Log::info("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Exception in List Session');
        }

        return $respData;
    }

    public function addSession($id)
    {
        $respData = [];
        
        if ($id === null || trim($id) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
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
                $respData = [
                    'success' => true,
                    'data' => json_decode($body, true)
                ]; 
            } else {
                $respData = $this->getErrorResponse('Error in Add Session');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse(json_encode($e));
        }

        return $respData;
    }

    public function conversation($sessionId, $user) {
        $respData = [];
        
        try {
            $http = new Client();

            $jid = $user . '@s.whatsapp.net';

            $url = $this->getUrl('/'.$sessionId.'/chats' . '/' . $jid);
            Log::info("url: " . $url);

            $headers = $this->getHeaders();
            
            $response = $http->get($url,[], ['headers' => $headers]);
            
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();

                $respData = [
                    'success' => true,
                    'data' => json_decode($body, true)
                ];

                Log::info("respData: " . json_encode($respData));
            } else {

                $body = $response->getBody()->getContents();
                $respData = [
                    'success' => false,
                    'data' => json_decode($body, true)
                ];  

                Log::info("respData: " . json_encode($respData));
            }
        }
        catch (NetworkException $e) {
            Log::info("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Exception in List Session');
        }

        return $respData;
    }

    public function sendMessage($session, $user, $message)
    {
        $respData = ['success' => true];
     
        if ($session === null || trim($session) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        if ($user === null || trim($user) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        if ($message === null || trim($message) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
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
                $respData['success'] = true;
            } else {
                $respData = $this->getErrorResponse('Error in Add Session');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Error in Add Session');
        }

        return $respData;
    }

    public function enqueue($data) {
        return TableRegistry::getTableLocator()
            ->get('WaQueue')
            ->enqueue($data);
    }

    public function normalizePhone($phone) {

        preg_match_all($this->phone_regex, $phone, $matches);

        if (count($matches) >= 6) {
            $international = $matches[2][0];
            if ($international != null && $international != '') {
                return $international . $matches[3][0] . $matches[4][0] . $matches[5][0];
            }
        }
        return null;
    }
}