<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\Routing\Asset;
use Cake\Utility\Text;

/**
 * Persone Controller
 *
 * @property \App\Model\Table\PersoneTable $Persone
 * @method \App\Model\Entity\Persone[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersoneController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('Paginator');
    //$this->Authentication->allowUnauthenticated(['getList','index','view']);
  }


  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $tags = $this->request->getQuery('tags');
    if (isset($tags[0]) && is_array($tags) && !empty($tags[0])) {
      if (count($tags) == 1 && is_string($tags[0])) {
        $tags = explode(',', $tags[0]);
      }
      $query = $this->Persone->find('tagged', ['slug' => $tags]);
    } else {
      $query = $this->Persone->find();
    }

    $query->contain(['Tags']);

    $q = $this->request->getQuery('q');
    if (!empty($q)) {
      $query->where(['OR' => [
        'Persone.Nome LIKE' => "%$q%",
        'Persone.Cognome LIKE' => "%$q%",
        'Persone.DisplayName LIKE' => "%$q%",
        'Persone.Societa LIKE' => "%$q%",
      ]]);
    }


    $persone = $this->paginate($query);
    $pagination = $this->Paginator->getPagingParams();
    $this->set(compact('persone', 'pagination'));
    $this->viewBuilder()->setOption('serialize', ['persone', 'pagination']);
  }

  /**
   * View method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $persone = $this->Persone->get($id, [
      'contain' => ['Tag'],
    ]);

    $this->set(compact('persone'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $persone = $this->Persone->newEmptyEntity();
    if ($this->request->is('post')) {
      $persone = $this->Persone->patchEntity($persone, $this->request->getData());
      if ($this->Persone->save($persone)) {
        $this->Flash->success(__('The persone has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The persone could not be saved. Please, try again.'));
    }
    $this->set(compact('persone'));
  }

  /**
   * Edit method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $persone = $this->Persone->get($id, [
      'contain' => ['Tags'],
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $persone = $this->Persone->patchEntity($persone, $this->request->getData());
      if ($this->Persone->save($persone)) {
        $this->Flash->success(__('The persone has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The persone could not be saved. Please, try again.'));
    }
    $tags = $this->Persone->Tags->find('list', ['keyField' => 'slug']);
    $this->set(compact('persone'));
  }

  /**
   * Delete method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $persone = $this->Persone->get($id);
    if ($this->Persone->delete($persone)) {
      $this->Flash->success(__('The persone has been deleted.'));
    } else {
      $this->Flash->error(__('The persone could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function sendMail()
  {
    //Puoi passare in post un elenco di id
    $ids = $this->request->getData('ids');

    //Se invece passi la query domina questa
    $tags = $this->request->getQuery('tags');
    if (isset($tags[0]) && is_array($tags) && !empty($tags[0])) {
      if (count($tags) == 1 && is_string($tags[0])) {
        $tags = explode(',', $tags[0]);
      }
      $query = $this->Persone->find('tagged', ['slug' => $tags]);
    } else {
      $query = $this->Persone->find();
    }

    $q = $this->request->getQuery('q');
    if (!empty($q)) {
      $query->where(['OR' => [
        'Persone.Nome LIKE' => "%$q%",
        'Persone.Cognome LIKE' => "%$q%",
        'Persone.DisplayName LIKE' => "%$q%",
        'Persone.Societa LIKE' => "%$q%",
      ]]);
    }

    $invia_test = false;
    if ($this->request->is('post')) {
      $dt = $this->request->getData();
      $res = $query->first()->toArray();
      //Sostituisco i valori nel subject
      $subject = Text::insert(
        $dt['subject'],
        $res
      );
      //Sostituisco i valori nel template
      $body = Text::insert(
        $dt['body'],
        $res
      );

      if (array_key_exists('invia-test', $dt)) {
        $mailer = new Mailer('default');
        $mailer->setFrom([$dt['sender_email'] => $dt['sender_name']])
          ->setEmailFormat('html')
          ->setTo($dt['test'])
          ->setSubject($subject)
          ->viewBuilder()
          ->setLayout($dt['layout']);

        if (Configure::read('MailLogo')) {
          $mailer->setAttachments([
            'logo.png' => [
              'file' => WWW_ROOT .  Asset::imageUrl(Configure::read('MailLogo')),
              'mimetype' => 'image/png',
              'contentId' => '12345'
            ]
          ]);
        }


        $mailer->deliver($body);
      }
    }

    $this->set('count', $query->count());
    $this->set('ids', $ids);
  }
}
