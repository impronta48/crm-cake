<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\Mailer\Mailer;
use Cake\Routing\Asset;
use Cake\Utility\Text;
use EmailQueue\EmailQueue;
use EmailQueue\Model\Table\EmailQueueTable;
use App\Model\Table\PersoneTable;

/**
 * Campaigns Controller
 *
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CampaignsController extends AppController
{
  protected PersoneTable $Persone;

  public function delete($id)
  {
    $this->Crud->on('beforeDelete', function (\Cake\Event\EventInterface $event) {
      // Stop the delete event, the entity will not be deleted
      if ($event->getSubject()->entity->sent !== null) {
        $event->stopPropagation();
      }
    });

    return $this->Crud->execute();
  }

  public function edit($id = null)
  {
    try {
      if (empty($id)) {
        $campaign = $this->Campaigns->newEmptyEntity();
        //Devo memorizzare anche i campi impliciti che non sono nel form
        $campaign->querystring = htmlspecialchars($_SERVER['QUERY_STRING']);
        $campaign->user_id = $this->request->getAttribute('identity');
        if (intval($id)) {
          $campaign->id = $id;
        }
      } else {
        if (intval($id)) {
          $campaign = $this->Campaigns->get($id);
        }
        if (empty($campaign)) {
          throw new NotFoundException("Impossibile modificare la campagna $id perchè non esiste");
        }
      }

      if ($this->request->is(['post', 'put'])) {

        $dt = $this->request->getData();

        //Salvo la campagna
        $campaign = $this->Campaigns->patchEntity($campaign, $dt);

        //Salvataggio
        if ($this->Campaigns->save($campaign)) {
          $id = $campaign->id;
        } else {
          $success = false;
          $this->set(compact('success'));
          $this->viewBuilder()->setOption('serialize', ['success']);
          return;
        }

        $this->Persone = $this->fetchTable('Persone');

        $delta = [];
        $count_delta = 0;
        $count = 0;
        $ids = [];

        if (
          array_key_exists('invia-test', $dt) ||
          array_key_exists('invia', $dt) ||
          array_key_exists('invia-delta', $dt)
        ) {

          //Invio mail di test
          if (array_key_exists('invia-test', $dt)) {
            $persona = $this->Persone->find()->first();
            $this->test($id, $persona->id);
          } else {

            // //Se invece passi la query domina questa
            // $tags = $this->request->getQuery('tags');
            // if (isset($tags[0]) && is_array($tags) && !empty($tags[0])) {
            //   if (count($tags) == 1 && is_string($tags[0])) {
            //     $tags = explode(',', $tags[0]);
            //   }
            //   $query = $this->Persone->find('tagged', ['slug' => $tags]);
            // } else {
            //   $query = $this->Persone->find();
            // }

            // $q = $this->request->getQuery('q');
            // if (!empty($q)) {
            //   $query->where(['OR' => [
            //     'Persone.Nome LIKE' => "%$q%",
            //     'Persone.Cognome LIKE' => "%$q%",
            //     'Persone.DisplayName LIKE' => "%$q%",
            //     'Persone.Societa LIKE' => "%$q%",
            //   ]]);
            // }

            // $nazione = $this->request->getQuery('nazione');
            // if (!empty($nazione)) {
            //   //Se c'è una virgola cerco in OR
            //   if (strpos($nazione, ',')) {
            //     $nazione =  array_map('trim', explode(',', $nazione));
            //     $query->where(['Nazione IN' => $nazione]);
            //   } else {
            //     $query->where(['Nazione LIKE' => "$nazione%"]);
            //   }
            // }

            // $provincia = $this->request->getQuery('provincia');
            // if (!empty($provincia)) {
            //   //Se c'è una virgola cerco in OR
            //   if (strpos($provincia, ',')) {
            //     $provincia =  array_map('trim', explode(',', $provincia));
            //     $query->where(['Provincia IN' => $provincia]);
            //   } else {
            //     $query->where(['Provincia' => $provincia]);
            //   }
            // }

            //Passo dall'esterno gli ids
            $ids = $this->request->getData('ids');
            if ($ids == null || count($ids) <= 0) {
              $success = false;
              $this->set(compact('success'));
              $this->viewBuilder()->setOption('serialize', ['success']);
              return;
            }

            $persone_ids = $ids;

            $query = $this->Persone->find();
            $query->where(['id IN' => $ids]);
            $count = $query->count();
            // $persone = $query->select(['id']);
            // $persone_ids = [];
            // foreach ($persone as $p) {
            //   $persone_ids[] = $p->id;
            // }

            //Metto le mail nella coda per la vera spedizione
            if (array_key_exists('invia', $dt)) {
              
              if ($this->sendAll($id, $persone_ids)) {
                //Salvo la data di invio della campagna
                $campaign->sent = \Cake\I18n\DateTime::now();
              }

              if ($this->Campaigns->save($campaign)) {
                $msg = "Campagna salvata con successo";
                // $this->Flash->success($msg);
              } else {
                $msg = "Impossibile salvare la campagna";
                // $this->Flash->error($msg);
                $success = false;
                $this->set(compact('success'));
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
              }
            }

            if (!empty($id)) {
              $delta = $this->delta($id, $persone_ids);
            } else {
              $delta = [];
            }
            $count_delta = count($delta);

            //Metto le mail nella coda per la vera spedizione (solo di quelli che non hanno ricevuto)
            if (array_key_exists('invia-delta', $dt)) {
              if (count($delta) > 0) {
                $this->sendAll($id, $delta);
                if ($this->Campaigns->save($campaign)) {
                  $msg = "Campagna salvata con successo";
                  // $this->Flash->success($msg);
                } else {
                  $msg = "Impossibile salvare la campagna";
                  // $this->Flash->error($msg);
                  $success = false;
                  $this->set(compact('success'));
                  $this->viewBuilder()->setOption('serialize', ['success']);
                  return;
                }
              }
            }

            //Se ero in add faccio un redirect su edit
            // if (empty($id)) {
            //   $this->redirect([$campaign->id, '?' => $this->request->getQuery()]);
            // }
          }
        }
      }

      $success = true;

      $this->set(compact('success'));
      $this->set(compact('campaign'));
      $this->set(compact('delta'));
      $this->set(compact('count_delta'));
      $this->set(compact('count'));
      $this->set(compact('ids'));
      $this->viewBuilder()->setOption('serialize', ['success', 'campaign', 'delta', 'count_delta', 'count', 'ids']);
    } catch (\Exception $e) {

      $success = false;
      $this->set(compact('success'));
      $this->viewBuilder()->setOption('serialize', ['success']);
    }
  }

  public function status($id)
  {
    //Cerco il subject della campagna $id
    $campaign = $this->Campaigns->get($id);
    if (empty($campaign)) {
      throw new NotFoundException("La campagna $id non esiste");
    }
    //Cerco nella mailqueue tutti i destinatari di quella campagna e il loro stato  
    $equ = new EmailQueueTable();
    $destinatari = $equ->status($id)->toArray();

    $this->set(compact('destinatari'));
    $this->viewBuilder()->setOption('serialize', ['destinatari']);
  }

  public function delta($id, $persone_ids)
  {
    //Cerco il subject della campagna $id
    $campaign = $this->Campaigns->get($id);
    if (empty($campaign)) {
      throw new NotFoundException("La campagna $id non esiste");
    }
    $subject = $campaign->subject;

    //Cerco nella mailqueue tutti i destinatari di quella campagna e il loro stato
    $connection = ConnectionManager::get('default');
    $pids = implode(",", $persone_ids);
    $sql = "SELECT p.id from persone p where p.id IN ($pids) AND p.EMail not in (select email from email_queue where subject = :sub )";

    $delta = $connection->execute($sql, [
      'sub' => $subject
    ])->fetchAll('assoc');

    $persone_ids = [];
    foreach ($delta as $p) {
      $persone_ids[] = $p['id'];
    }
    //Restituisco un array di id per quei destinatari
    return $persone_ids;
  }

  private function test($id, $persona_id)
  {
    $this->Persone = $this->fetchTable('Persone');

    $persone = $this->Persone->findById($persona_id);
    $persone->disableHydration();
    $persona = $persone->first();
    $campaign = $this->Campaigns->get($id);
    if (empty($campaign)) {
      throw new NotFoundException("La campagna $id non esiste");
    }

    if (Configure::read('MailLogo')) {
      $logoAttachment = [
        'logo.png' => [
          // 'file' => WWW_ROOT . Asset::imageUrl(Configure::read('MailLogo')),
          'file' => WWW_ROOT . Configure::read('MailLogo'),
          'mimetype' => 'image/png',
          'contentId' => '12345'
        ]
      ];
    }

    $subject = Text::insert(
      $campaign['subject'],
      $persona
    );
    //Sostituisco i valori nel template
    $body = Text::insert(
      $campaign['body'],
      $persona
    );
    $mailer = new Mailer('default');
    $mailer->setFrom([$campaign['sender_email'] => $campaign['sender_name']])
      ->setEmailFormat('html')
      ->setTo($campaign['test_email'])
      ->setSubject($subject)
      ->setViewVars(['body' => $body])
      ->viewBuilder()
      ->setTemplate('dynamic')
      ->setLayout($campaign['layout']);

    if (Configure::read('MailLogo')) {
      $mailer->setAttachments($logoAttachment);
    }

    $mailer->deliver();
  }

  private function sendAll($id, $persone_id)
  {
    // $this->loadModel('Persone');
    $this->Persone = $this->fetchTable('Persone');

    $campaign = $this->Campaigns->get($id);
    if (empty($campaign)) {
      throw new NotFoundException("La campagna $id non esiste");
    }

    if (Configure::read('MailLogo')) {
      $logoAttachment = [
        'logo.png' => [
          // 'file' => WWW_ROOT .  Asset::imageUrl(Configure::read('MailLogo')),
          'file' => WWW_ROOT . Configure::read('MailLogo'),
          'mimetype' => 'image/png',
          'contentId' => '12345'
        ]
      ];
    }

    $query = $this->Persone->find()
      ->where(['id IN ' => $persone_id]);

    foreach ($query as $r) {
      $to = $r['EMail'];
      //Se questo utente non ha la mail, ignoro
      if (empty($to)) {
        continue;
      }

      $subject = Text::insert(
        $campaign['subject'],
        $r->toArray()
      );
      //Sostituisco i valori nel template
      $body = Text::insert(
        $campaign['body'],
        $r->toArray()
      );

      $data = ['body' => $body];
      $options = [
        'subject' => $subject,
        'layout' => $campaign['layout'],
        'template' => 'dynamic',
        'config' => 'default',
        'send_at' => \Cake\I18n\DateTime::now(),
        'format' => 'html',
        'from_name' => $campaign['sender_name'],
        'from_email' => $campaign['sender_email'],
        'campaign_id' => $id,
      ];

      if (Configure::read('MailLogo')) {
        $options['attachments'] = $logoAttachment;
      }

      return EmailQueue::enqueue($to, $data, $options);
    }
  }
}
