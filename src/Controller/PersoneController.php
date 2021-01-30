<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Mailer\Mailer;
use Cake\Routing\Asset;
use Cake\Utility\Text;
use EmailQueue\EmailQueue;
use Exception;
use Psy\Util\Str;

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
        $tagAr = explode(',', $tags[0]);
        $tags = implode("+", $tagAr); //metto i tag in AND
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
        'Persone.EMail LIKE' => "%$q%",
        'Persone.Cellulare LIKE' => "%$q%",
      ]]);
    }

    $nazione = $this->request->getQuery('nazione');
    if (!empty($nazione)) {
      //Se c'è una virgola cerco in OR
      if (strpos($nazione, ',')) {
        $nazione =  array_map('trim', explode(',', $nazione));
        $query->where(['Nazione IN' => $nazione]);
      } else {
        $query->where(['Nazione LIKE' => "$nazione%"]);
      }
    }

    try {
      $persone = $this->paginate($query);
    } catch (NotFoundException $e) {
      // Do something here like redirecting to first or last page.
      // $this->request->getAttribute('paging') will give you required info.
    }

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
}
