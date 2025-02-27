<?php

declare(strict_types=1);

namespace App\Controller;

class TagsController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();
    // $this->loadModel('Tags.Tags');
  }

  public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
	
		// $this->fetchTable('Dereuromark\Tags.Tags');
    // $this->fetchTable('Dereuromark/Tags.Tags');
    // $this->fetchTable('Dereuromark/Tags.TagsTable');
    // $this->fetchTable('Dereuromark/Tags.Tag');
    
    // $this->fetchTable('Dereuromark/cachephp-tags/Tags.Tags');
    // $this->fetchTable('Dereuromark/cachephp-tags.Tags');

    // $this->fetchTable('Dereuromark/Cachephp-tags/Tags.Tags');

    // $this->fetchTable('Tags.Tag');

    // $this->fetchTable('Dereuromark/Tags.Tags');

	}

  // public function index() : void 
  // {
  //   $tags = $this->MyTaggedTable->Tagged->find('cloud')->toArray();
  //   $this->set(compact('tags'));  
  // }

  // public function index(): void
  // {
  //   $search = $this->request->getQuery('search');

  //   $tags = $this->Tags->find('list', ['keyField' => 'slug'])
  //     ->where(['slug LIKE ' => "%$search%"])
  //     ->order(['slug'])
  //     ->limit(15);

  //   $opts = [];
  //   // il risultato deve essere restituito come array di oggetti, diversamente javascript non sarebbe in grado di mantenere
  //   // l'ordine degli elementi generato da php
  //   foreach ($tags as $k => $v) {
  //     $opts[] = [
  //       'id' => $k,
  //       'name' => $v
  //     ];
  //   }

  //   $this->set('tags', $opts);
  //   $this->viewBuilder()->setOption('serialize', ['tags']);
  // }
}
