<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Exception;

use Cake\Collection\CollectionInterface;
use Cake\ORM\Query\SelectQuery;

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

  public function tags()
  {
    // $tags = $this->Persone->Tagged->find('cloud')->toArray();

    $query = $this->Persone->Tagged->find();
    $query = $this->findCloud($query);
    $tags = $query->toArray();

    $this->set(compact('tags'));
    $this->viewBuilder()->setOption('serialize', ['tags']);
  }

  private function findCloud(SelectQuery $query)
  {
    $groupBy = ['Tagged.tag_id', 'Tags.id', 'Tags.slug', 'Tags.label'];
    $fields = $groupBy;
    $fields['counter'] = $query->func()->count('*');

    // FIXME or remove
    // Support old code without the counter cache
    // This is related to https://github.com/CakeDC/tags/issues/10 to work around a limitation of postgres
    /*
		$field = $this->getDataSource()->fields($this->Tag);
		$field = array_merge($field, $this->getDataSource()->fields($this, null, 'Tagged.tag_id'));
		$fields = 'DISTINCT ' . implode(',', $field);
		$groupBy = null;
		*/

    // $options = [
    // 	'minSize' => 10,
    // 	'maxSize' => 20,
    // 	'contain' => 'Tags',
    // 	'fields' => $fields,
    // 	'group' => $groupBy,
    // ];
    if ($query->clause('where') === null) {
      $query->where(['Tags.id IS NOT' => null]);
    }
    if ($query->clause('order') === null) {
      $query->orderbyAsc('Tags.label');
    }

    $query->formatResults(function (CollectionInterface $results) {
      // $results = static::calculateWeights($results->toArray());

      return $results;
    });

    return $query->find(
      'all',
      minSize: 10,
      maxSize: 20,
      contain: 'Tags',
      fields: $fields,
      group: $groupBy
    );
  }

  public function indexNoPaginate() {
    $query = $this->Persone->find('all');

    $query->find('search', search: $this->request->getQueryParams());

    $selection = false;

    $filter = $this->request->getQuery('filter');
    if ($filter != null && $filter != '') {
      // $query->where(['Nazione' => $nazione]);
      $selection = $selection || true;
    }

    $tags = $this->request->getQuery('tags');
    if ($tags != null && count($tags) > 0) {
      $query->find('tagged', slug: $tags);
      $selection = $selection || true;
    }

    $provincia = $this->request->getQuery('provincia');
    if ($provincia != null && $provincia != '') {
      $query->where(['Provincia' => $provincia]);
      $selection = $selection || true;
    }

    $nazione = $this->request->getQuery('nazione');
    if ($nazione != null && $nazione != '') {
      $query->where(['Nazione' => $nazione]);
      $selection = $selection || true;
    }

    if (!$selection) {
      $success = false;
      $data = [];
    }
    else {
      $success = true;
      $data = $query->toArray();
    }

    $this->set(compact('data'));
    $this->set(compact('success'));
    $this->viewBuilder()->setOption('serialize', ['success','data']);
  }

  public function index()
  {
    // Log::info('myIndex');

    $this->Crud->on('beforePaginate', function (\Cake\Event\EventInterface $event) {
      // Log::info('on beforePaginate');

      $tags = $this->request->getQuery('tags');
      if ($tags != null && count($tags) > 0) {
        $event->getSubject()->query->find('tagged', slug: $tags);
      }

      $provincia = $this->request->getQuery('provincia');
      if ($provincia != null && $provincia != '') {
        $event->getSubject()->query->where(['Provincia' => $provincia]);
      }

      $nazione = $this->request->getQuery('nazione');
      if ($nazione != null && $nazione != '') {
        $event->getSubject()->query->where(['Nazione' => $nazione]);
      }
    });

    return $this->Crud->execute();
  }

  public function updateMore() {
    Log::info("updateMore");
    $field = $this->request->getData('field');
    $value = $this->request->getData('value');
    Log::info("field: " . $field);
    Log::info("value: " . $value);
    
    $this->Crud->action()->setConfig('field', $field);
    $this->Crud->action()->setConfig('value', $value);

    $this->Crud->on('beforeBulk', function (\Cake\Event\EventInterface $event) {
      Log::info('on beforeBulk');
      $ids = $event->getSubject()->ids;
      Log::info('ids: ' . json_encode($ids));
      // $event->stopPropagation();
    });

    return $this->Crud->execute();
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
  //   return $this->response
  //               ->withType('json')
  //               ->withStringBody(json_encode($resp));
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

    $success = false;

    if ($this->Persone->saveMany($persone)) {
      $success = true;
    } 

    $this->set(compact('success'));
    $this->viewBuilder()->setOption('serialize', ['success']);
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
