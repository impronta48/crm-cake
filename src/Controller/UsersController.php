<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\NotAcceptableException;

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
        $this->Authentication->allowUnauthenticated(['telegramLogin']);
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
        $bot_token = $this->request->getData('bot_token');
        $telegram_chat_id = $this->check_telegram_authorization($auth_data, $bot_token);

        if (empty($telegram_chat_id)) {
            return $this->response
                ->withType('json')
                ->withStringBody(json_encode($respData));
            return;
        }

        $user = $this->Users->find()->where(['telegram_id' => $telegram_chat_id])->first();
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
}
