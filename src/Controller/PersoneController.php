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
    //$this->Authentication->allowUnauthenticated(['update']);
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

    $provincia = $this->request->getQuery('provincia');
    if (!empty($provincia)) {
      //Se c'è una virgola cerco in OR
      if (strpos($provincia, ',')) {
        $provincia =  array_map('trim', explode(',', $provincia));
        $query->where(['Provincia IN' => $provincia]);
      } else {
        $query->where(['Provincia' => $provincia]);
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
    if (is_null($id)) {
      $ids = $this->request->getData('ids');
      if (empty($ids)) {
        throw new Exception("Nulla da cancellare");
      }
    } else {
      $ids = $id;
    }
    $persone = $this->Persone->find()->where(['id IN' => $ids]);
    if (empty($persone)) {
      throw new Exception("Nulla da cancellare");
    }

    if ($this->Persone->deleteMany($persone)) {
      $this->Flash->success(__('The persone has been deleted.'));
    } else {
      $this->Flash->error(__('The persone could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function update()
  {
    $this->autoRender = false;
    $remoteP = $this->request->getData();
    $email = $remoteP['EMail'];
    if (empty($email)) {
      throw new NotFoundException("il contatto non ha la mail");
    }

    $localP = $this->Persone->find()
      ->where(['EMail' => $email])
      ->first();

    if (!empty($localP)) {
      $remoteP['id'] = $localP->id;
      $remoteP['tag_list'] = $localP->tag_list . ', ' . $remoteP['tag_list'];
    } else {
      $localP = $this->Persone->newEmptyEntity();
    }

    $remoteP =  $this->Persone->patchEntity($localP, $remoteP);
    if ($this->Persone->save($remoteP)) {
      return;
    } else {
      debug($remoteP->getErrors());
      throw new Exception("Impossibile salvare");
    }
  }

  public function addTags()
  {
    $this->request->allowMethod(['post', 'put']);
    $ids = $this->request->getData('ids');
    $tags = $this->request->getData('tags');
    if (empty($ids) || empty($tags)) {
      throw new Exception("Nessuna persona da taggare o nessun tag inserito");
    }
    $persone = $this->Persone->find()
      ->contain(['Tags'])
      ->where(['id IN' => $ids]);

    if (empty($persone)) {
      throw new Exception("Nessuna persona da taggare");
    }

    foreach ($persone as $p) {
      $this->Persone->patchEntity($p, ['tag_list' =>  $p->tag_list . ", $tags"]);
    }

    if ($this->Persone->saveMany($persone)) {
      $this->Flash->success(__('The persone has been deleted.'));
    } else {
      $this->Flash->error(__('The persone could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
