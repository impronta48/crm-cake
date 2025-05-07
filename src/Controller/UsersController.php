<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\NotAcceptableException;
use Cake\Core\Configure;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['telegramLogin','login']);
    }

    /**
     * Funzione per login con telegram
     */
    public function telegramLogin(){

        $this->autoRender = false;
        
        $respData = ['success' => false, 'user' => ''];

        if (!($this->request->is('json'))) {
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
            return;
        }

        $auth_data = $this->request->getData('user');
        $domain_name = $this->request->getData('domain_name');
        if(empty($domain_name)){
            $bot_token = Configure::read('Telegram.telergam_token');  
        }
        else{
            $bot_token = Configure::read('Telegram.'.$domain_name.'.telergam_token');   
        }
        $telegram_chat_id = $this->check_telegram_authorization($auth_data, $bot_token);

        if (empty($telegram_chat_id)) {
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
            return;
        }

        $user = $this->Users->find()->where(['telegram_chat_id' => $telegram_chat_id])->first();
        if (empty($user)) {
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
            return;
        }

        $user->authToken = $this->Users->generateAuthToken($user->id);

        $respData = ['success' => true, 'user' => $user];

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));

    }

    private function check_telegram_authorization($auth_data, $bot_token = null)
    {
        if (empty($bot_token)) {
            return null;
        }

        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }

        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        if (strcmp($hash, $check_hash) !== 0) {
            return null;
        }

        if ((time() - $auth_data['auth_date']) > 86400) {
            return null;
        }
        $chatId = $auth_data['id'];

        return $chatId;
    }

    public function getUser() {
        $this->autoRender = false;

        $respData = ['success' => false, 'userId' => ''];

        if (!($this->request->is('json'))) {
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
            return;
        }

        $authToken = $this->request->getData('token');

        $user = $this->Users->getUserIdFromAuthToken($authToken);
        if ($user != null) {
            $respData = ['success' => true, 'userId' => $user];
        }

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }

    public function logout()
    {
        $respData = ['success' => true];

        $this->Authentication->logout();

        return $this->response
            ->withType('json')
            ->withStringBody(json_encode($respData));
    }
    public function login()
    {
    //     // Check for redirect loops
    // $redirectParam = $this->request->getQuery('redirect');
    // if ($redirectParam && (strpos($redirectParam, '/users/login') !== false || strlen($redirectParam) > 200)) {
    //     // Redirect loop detected, redirect to a safe location
    //     return $this->redirect('/');
    // }
        // Check if we have POST data
        if ($this->request->is('post')) {
            // Get the submitted data
            $data = $this->request->getData();
            debug('Login attempt with username: ' . ($data['username'] ?? 'not provided'));
            
            // Check if user exists (for debugging)
            $user = $this->Users->find()
                ->where(['username' => $data['username'] ?? ''])
                ->first();
            
            if ($user) {
                debug('User found in database, ID: ' . $user->id);
            } else {
                debug('User not found in database');
            }
        }

        $result = $this->Authentication->getResult();
        $user = $this->Authentication->getIdentity();
        $request = $this->getRequest();
        $user = $request->getAttribute('identity');
        // Check for redirect loops - prevent nested redirects
    //    // For web interface, redirect to intended destination
    //    $redirect = $redirectParam && !strpos($redirectParam, '/users/login') ? $redirectParam : '/';
    //    return $this->redirect($redirect);

        // // Add debugging
        // debug($result);
        // debug($result->getStatus());
        // debug($result->getErrors());
        
        // If the user is logged in send them away.
        if ($result && $result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/home';
            return $this->redirect($target);
        }
        
        if ($this->request->is('post')) {
            // Check for empty password
            $data = $this->request->getData();
            if (empty($data['password'])) {
                $this->Flash->error('Password cannot be empty');
            } else {
                $this->Flash->error('Invalid username or password');
            }
        }
    }
}
