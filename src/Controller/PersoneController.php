<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Exception;

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
  }

  public function beforeFilter(\Cake\Event\EventInterface $event)
  {
    $this->Crud->addListener('Crud.Search', 'App\Controller\PersoneController', [
      // Events to listen for and apply search finder to query.
      'enabled' => [
        'Crud.beforeLookup',
        'Crud.beforePaginate'
      ],
      // Search collection to use
      'collection' => 'default'
    ]);

    parent::beforeFilter($event);
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  /* public function index()
  {
    $tags = $this->request->getQuery('tags');
    if (isset($tags[0]) && is_array($tags) && !empty($tags[0])) {
      if (count($tags) == 1 && is_string($tags[0])) {
        $tagAr = explode(',', $tags[0]);
        $tags = implode("+", $tagAr); //metto i tag in AND
      }
      $query = $this->Persone->find('tagged', slug: $tags);
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
      // $persone = $result->items();
      // $pagination = $result->pagingParams();
    } catch (NotFoundException $e) {
      // Do something here like redirecting to first or last page.
      // $this->request->getAttribute('paging') will give you required info.
    }

    // $pagination = $this->Paginator->getPagingParams();
    $pagination = $persone->pagingParams();
    
    $this->set(compact('persone', 'pagination'));
    $this->viewBuilder()->setOption('serialize', ['persone', 'pagination']);

    // $this->set(compact('persone'));
    // $this->viewBuilder()->setOption('serialize', ['persone']);
  }*/

  /**
   * View method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  // public function view($id = null)
  // {
  //   $persone = $this->Persone->get($id, contain: ['Tag']);

  //   $this->set(compact('persone'));
  // }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  // public function add()
  // {
  //   $persone = $this->Persone->newEmptyEntity();
  //   if ($this->request->is('post')) {
  //     $persone = $this->Persone->patchEntity($persone, $this->request->getData());
  //     if ($this->Persone->save($persone)) {
  //       $this->Flash->success(__('The persone has been saved.'));

  //       return $this->redirect(['action' => 'index']);
  //     }
  //     $this->Flash->error(__('The persone could not be saved. Please, try again.'));
  //   }
  //   $this->set(compact('persone'));
  // }

  /**
   * Edit method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  // public function edit($id = null)
  // {
  //   $persone = $this->Persone->get($id, contain: ['Tags']);
  //   if ($this->request->is(['patch', 'post', 'put'])) {
  //     $persone = $this->Persone->patchEntity($persone, $this->request->getData());
  //     if ($this->Persone->save($persone)) {
  //       $this->Flash->success(__('The persone has been saved.'));

  //       return $this->redirect(['action' => 'index']);
  //     }
  //     $this->Flash->error(__('The persone could not be saved. Please, try again.'));
  //   }
  //   $tags = $this->Persone->Tags->find('list', ['keyField' => 'slug']);
  //   $this->set(compact('persone'));
  // }

  /**
   * Delete method
   *
   * @param string|null $id Persone id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  // public function delete($id = null)
  // {
  //   $this->autoRender = false;

  //   // $this->request->allowMethod(['post', 'delete']);

  //   $resp = [
  //     'success' => false,
  //     'data' => []
  //   ];

  //   if (is_null($id)) {
  //     $ids = $this->request->getData('ids');
  //     if (empty($ids)) {
  //       $this->set(compact('resp'));
  //       return;
  //     }
  //   } else {
  //     $ids = $id;
  //   }

  //   try {
  //     $persone = $this->Persone->find()->where(['id IN' => $ids]);
  //     if (empty($persone)) {
  //       $this->set(compact('resp'));
  //       return;
  //     }

  //     if ($this->Persone->deleteMany($persone)) {
  //       $resp['success'] = true;
  //     }
  //   }
  //   catch(Exception $e) {
  //   }

  //   $this->set(compact('resp'));
  //   $this->viewBuilder()->setOption('serialize', ['resp']);
  //   // return $this->response
  //   //             ->withType('json')
  //   //             ->withStringBody(json_encode($resp));
  // }

  // public function update()
  // {
  //   $this->autoRender = false;
  //   $remoteP = $this->request->getData();
  //   $email = $remoteP['EMail'];
  //   if (empty($email)) {
  //     throw new NotFoundException("il contatto non ha la mail");
  //   }

  //   $localP = $this->Persone->find()
  //     ->where(['EMail' => $email])
  //     ->first();

  //   if (!empty($localP)) {
  //     $remoteP['id'] = $localP->id;
  //     $remoteP['tag_list'] = $localP->tag_list . ', ' . $remoteP['tag_list'];
  //   } else {
  //     $localP = $this->Persone->newEmptyEntity();
  //   }

  //   $remoteP =  $this->Persone->patchEntity($localP, $remoteP);
  //   if ($this->Persone->save($remoteP)) {
  //     return;
  //   } else {
  //     debug($remoteP->getErrors());
  //     throw new Exception("Impossibile salvare");
  //   }
  // }

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


  public function import()
  {
    if ($this->request->is(['post'])) {
      $attachment = $this->request->getData('excelfile');
      $tags = $this->request->getData('tags');
      Log::info("Offices import - received the xls file");
      $name = $attachment['name'];
      $fname = $attachment['tmp_name'];
      $error = $attachment['error'];

      if ($error != 0) {
        Log::info("Offices import - error while uploading the file" . var_export($attachment, true));
        return $this->Flash->error(__('Errore nell\'apertura del file.'));
      }

      $filename = TMP . $name;

      move_uploaded_file($fname, $filename);
      Log::info("Offices import - file moved in correctly");

      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
      $sheetData = $spreadsheet->getActiveSheet()->toArray();
      //La prima riga contiene i titoli e la scarto
      array_shift($sheetData);

      if (empty($spreadsheet)) {
        return $this->Flash->error(__('Il file importato e vuoto.'));
        Log::info("Offices import - the spreadshit is empty");
      }


      foreach ($sheetData as $participant) {
        $errorMsg = false;
        try {
          $p = [];
          $p['email'] = $participant[0];
          $p['first_name'] = $participant[1] ?? null;
          $p['last_name'] = $participant[2] ?? null;
          $this->Persone->add($p, $tags);
        } catch (\Exception $e) {
          $errorMsg = $e->getMessage();
        }
      }
    }
  }
}
