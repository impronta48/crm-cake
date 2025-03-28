<?php

namespace App\Chat;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Http\Client\Exception\NetworkException;
use Cake\Core\Configure;

use Cake\ORM\TableRegistry;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class WhatsappService 
{
    public const UNKNOWN = 'UNKNOWN';
    public const TEXT = 'TEXT';
    public const FILE = 'FILE';
    public const IMAGE = 'IMAGE';
    public const VIDEO = 'VIDEO';
    public const POSITION = 'POSITION';

    public const BASEDIR = TMP . 'whatsapp';

    private static $instance = null;
    
    private const WA_POSTFIX = '@s.whatsapp.net';
    private string $phone_regex = '~[^\d]{0,7}(00)?(\d{1,2})?[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~';

    private string $wa_base_url = '';
    private string $wa_api_key = '';
    private string $freescout_webhook = '';
    
    private function __construct()
    {
        $this->wa_base_url = Configure::read('WhatsApp.base_url') . ":" . Configure::read('WhatsApp.port');
        $this->wa_api_key = Configure::read('WhatsApp.api_key');
        $this->freescout_webhook = Configure::read('WhatsApp.freescout_webhook');
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

            $jid = $user . self::WA_POSTFIX; //'@s.whatsapp.net';

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

        $phone = $this->extractPhone($user);

        try {
            $http = new Client();

            $url = $this->getUrl('/'.$session.'/messages/send');
            $headers = $this->getHeaders();

            $data = [
                        "jid" => $phone . self::WA_POSTFIX, //"@s.whatsapp.net",  //numero deve iniziare con 39
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

    public function extractPhone($wa_user) {
        return str_replace(self::WA_POSTFIX, "", $wa_user);
    }

    public function forwardWebhook($data) {

        if( $this->freescout_webhook == null || $this->freescout_webhook == '' || $data == null ) {
            $respData = $this->getErrorResponse('Error in forward webhook');
            return $respData;
        }

        $forwardData = $this->readWhatsappData($data);
        if ($forwardData['success'] == false) {
            return $forwardData;
        }

        try {
            $http = new Client();

            $url = $this->freescout_webhook;
            $headers = []; //['X-API-Key' => $this->wa_api_key, 'decode_content' => 'json']; 
            $response = $http->post($url, json_encode($forwardData), ['type' => 'json', 'headers' => $headers]);
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();
                $respData = json_decode($body, true);
                $respData['success'] = true;
            } else {
                $respData = $this->getErrorResponse('Error in forward webhook');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Error in forward webhook');
        }

        return $respData;
    }

    public function readWhatsappData($data) {

        $event = $data['event'] ?? '';
        if ($event !== 'messages.upsert') {
            $respData = $this->getErrorResponse('Event ' . $event . '. messages.upsert expected');
            return $respData;
        }

        $messages = $data['data']['messages'];
        if ($messages == null) {
            $respData = $this->getErrorResponse('Empty data.messages');
            return $respData;
        }

        $messageData = [];
        $messageData['type'] = self::UNKNOWN;

        $messageData['sessionId'] = $data['sessionId'] ?? '';
        $messageData['remoteJid'] = $messages['key']['remoteJid'] ?? '';
        $messageData['phone'] = $this->extractPhone($messageData['remoteJid']) ?? '';
        $messageData['pushName'] = $messages['pushName'] ?? '';
        $messageData['messageTimestamp'] = $messages['messageTimestamp'] ?? '';
        
        $conversation = $messages['message']['conversation'];
        
        $documentWithCaptionMessage = $messages['message']['documentWithCaptionMessage'];

        $documentMessage = $messages['message']['documentMessage'];

        $imageMessage = $messages['message']['imageMessage'];

        $videoMessage = $messages['message']['videoMessage'];

        $locationMessage = $messages['message']['locationMessage'];

        if ($conversation != null) {
            // Messaggio di testo
            $messageData['type'] = self::TEXT;
            $messageData['conversation'] = $conversation;
        }
        else if ($documentWithCaptionMessage != null) {
            // File con caption
            $messages['message'] = $documentWithCaptionMessage['message'];

            $fileName = $documentWithCaptionMessage['message']['documentMessage']['fileName'] ?? '';
            $mediaData = $this->cacheMedia($data['sessionId'], $messages, $fileName);

            $messageData['type'] = self::FILE;
            $messageData['url'] = $mediaData['url'] ?? '';
            $messageData['mimetype'] = $documentWithCaptionMessage['message']['documentMessage']['mimetype'] ?? '';
            $messageData['title'] = $documentWithCaptionMessage['message']['documentMessage']['title'] ?? '';
            $messageData['caption'] = $documentWithCaptionMessage['message']['documentMessage']['caption'] ?? '';
            $messageData['fileLength'] = $documentWithCaptionMessage['message']['documentMessage']['fileLength'] ?? '';            
            $messageData['fileName'] = $fileName;
        }
        else if ($documentMessage != null) {
            // File senza caption
            $fileName = $documentMessage['fileName'] ?? '';
            $mediaData = $this->cacheMedia($data['sessionId'], $messages, $fileName);

            $messageData['type'] = self::FILE;
            $messageData['url'] = $mediaData['url'] ?? '';
            $messageData['mimetype'] = $documentMessage['mimetype'] ?? '';
            $messageData['title'] = $documentMessage['title'] ?? '';
            $messageData['fileLength'] = $documentMessage['fileLength'] ?? '';
            $messageData['fileName'] = $fileName;
        }
        else if ($imageMessage != null) {
            // Immagine
            $fileName = $this->buildFilename($imageMessage['mimetype']);
            $mediaData = $this->cacheMedia($data['sessionId'], $messages, $fileName);

            $messageData['type'] = self::IMAGE;
            $messageData['url'] = $mediaData['url'] ?? '';
            $messageData['mimetype'] = $imageMessage['mimetype'] ?? '';
            $messageData['caption'] = $imageMessage['caption'] ?? '';
            $messageData['fileLength'] = $imageMessage['fileLength'] ?? '';
            $messageData['width'] = $imageMessage['width'] ?? '';
            $messageData['height'] = $imageMessage['height'] ?? '';
        }
        else if ($videoMessage != null) {
            // Video
            $fileName = $this->buildFilename($videoMessage['mimetype']);
            $mediaData = $this->cacheMedia($data['sessionId'], $messages, $fileName);

            $messageData['type'] = self::VIDEO;
            $messageData['url'] = $mediaData['url'] ?? '';
            $messageData['mimetype'] = $videoMessage['mimetype'] ?? '';
            $messageData['caption'] = $videoMessage['caption'] ?? '';   
            $messageData['fileLength'] = $videoMessage['fileLength'] ?? ''; 
            $messageData['width'] = $videoMessage['width'] ?? '';
            $messageData['height'] = $videoMessage['height'] ?? '';
            $messageData['seconds'] = $videoMessage['seconds'] ?? '';
        }
        else if ($locationMessage != null) {
            // Posizione
            $messageData['type'] = self::POSITION;
            $messageData['degreesLatitude'] = $locationMessage['degreesLatitude'] ?? '';
            $messageData['degreesLongitude'] = $locationMessage['degreesLongitude'] ?? '';
        }

        if( $messageData['sessionId'] == '' || 
            $messageData['remoteJid'] == '' || 
            $messageData['phone'] == '' ||
            $messageData['pushName'] == '' ||
            $messageData['type'] == self::UNKNOWN ) {
            $respData = $this->getErrorResponse('No well formatted whatsapp messagge found');
            return $respData;
        }

        $respData = [
            'success' => true,
            'messageData' => $messageData
        ];

        return $respData;
    }


    private function buildFilename($mimetypeString)
    {
        $mimetype = explode('/', $mimetypeString);
        $fileName = 'Whatsapp' . rand(1000000, 9999999);
        if( count($mimetype)>1 ) {
            $fileName = $fileName . '.' . $mimetype[1];
        }
        return $fileName;
    }

    private function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'n4YveuAPNP';
        $secret_iv = 'GdVP3vfo4l';
        // hash
        $key = hash('sha256', $secret_key);
    
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    private function getAbsolutePath($fileName) {
        $dateDir = DIRECTORY_SEPARATOR . date("Ym");
        $dir = self::BASEDIR . $dateDir;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $encrypt = $this->encrypt_decrypt('encrypt', $dateDir . DIRECTORY_SEPARATOR . $fileName);

        $fullBaseUrl = Configure::read('App.fullBaseUrl');
        $api_url = $fullBaseUrl . '/api/whatsapp/downloadMedia/' . $encrypt;

        return [
            'absolute' => $dir . DIRECTORY_SEPARATOR . $fileName,
            'url' => $api_url
        ];
    }

    private function decryptAbsolutePath($encrypt) {
        $relativeFilePath = $this->encrypt_decrypt('decrypt', $encrypt);

        return self::BASEDIR . $relativeFilePath;
    }
    
    private function cacheMedia($session, $messages, $fileName)
    {
        $respData = ['success' => true];
     
        if ($session === null || trim($session) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        if ($messages === null) {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        if ($fileName === null || trim($fileName) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        try {
            $http = new Client();

            $url = $this->getUrl('/'.$session.'/messages/download');
            $headers = $this->getHeaders();

            $data = $messages;

            $response = $http->post($url, json_encode($data), ['type' => 'json', 'headers' => $headers]);
            if ($response->isSuccess()) {
                $body = $response->getBody()->getContents();

                $absolute = $this->getAbsolutePath($fileName);

                $myfile = fopen($absolute['absolute'], "w");
                if ($myfile != false) {
                    fwrite($myfile, $body);
                    fclose($myfile);
                    $respData = [
                        'success' => true,
                        'absolute' => $absolute['absolute'],
                        'url' => $absolute['url'],
                        'filename' => $fileName,
                    ];
                }
                else {
                    $respData = $this->getErrorResponse('Error in File download');
                }
                
            } else {
                $respData = $this->getErrorResponse('Error in File download');
            }
        }
        catch (NetworkException $e) {
            Log::error("Error: " . json_encode($e));
            $respData = $this->getErrorResponse('Error in File download');
        }

        return $respData;
    }

    public function downloadMedia($encrypt)
    {
        if ($encrypt === null || trim($encrypt) ==='') {
            $respData = $this->getErrorResponse('Invalid parameters');
            return $respData;
        }

        $filePath = $this->decryptAbsolutePath($encrypt);
        return $filePath;
    }
}