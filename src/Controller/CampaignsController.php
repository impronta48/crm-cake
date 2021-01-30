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

/**
 * Campaigns Controller
 *
 * @property \App\Model\Table\CampaignsTable $Campaigns
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CampaignsController extends AppController
{
  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $this->paginate = [
      'contain' => ['Users'],
      'order' => ['Campaigns.id DESC'],
    ];
    $campaigns = $this->paginate($this->Campaigns);

    $this->set(compact('campaigns'));
  }


  public function edit($id = null)
  {
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

    $this->loadModel('Persone');

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

    if ($this->request->is(['post', 'put'])) {
      $dt = $this->request->getData();

      //Salvo la campagna
      $campaign = $this->Campaigns->patchEntity($campaign, $dt);

      //Salvataggio
      if ($this->Campaigns->save($campaign)) {
        $msg = "Campagna salvata con successo";
        $this->Flash->success($msg);
      } else {
        $msg = "Impossibile salvare la campagna";
        $this->Flash->error($msg);
      }

      if (Configure::read('MailLogo')) {
        $logoAttachment = [
          'logo.png' => [
            'file' => WWW_ROOT .  Asset::imageUrl(Configure::read('MailLogo')),
            'mimetype' => 'image/png',
            'contentId' => '12345'
          ]
        ];
      }

      //Invio mail di test
      if (array_key_exists('invia-test', $dt)) {
        $res = $query->first()->toArray();
        $subject = Text::insert(
          $dt['subject'],
          $res
        );
        //Sostituisco i valori nel template
        $body = Text::insert(
          $dt['body'],
          $res
        );
        $mailer = new Mailer('default');
        $mailer->setFrom([$dt['sender_email'] => $dt['sender_name']])
          ->setEmailFormat('html')
          ->setTo($dt['test_email'])
          ->setSubject($subject)
          ->setViewVars(['body' => $body])
          ->viewBuilder()
          ->setTemplate('dynamic')
          ->setLayout($dt['layout']);

        if (Configure::read('MailLogo')) {
          $mailer->setAttachments($logoAttachment);
        }

        $mailer->deliver();
      }

      //Metto le mail nella coda per la vera spedizione
      if (array_key_exists('invia', $dt)) {

        foreach ($query as $r) {
          $to = $r['EMail'];
          //Se questo utente non ha la mail, ignoro
          if (empty($to)) {
            continue;
          }

          $subject = Text::insert(
            $dt['subject'],
            $r->toArray()
          );
          //Sostituisco i valori nel template
          $body = Text::insert(
            $dt['body'],
            $r->toArray()
          );

          $data = ['body' => $body];
          $options = [
            'subject' => $subject,
            'layout' => $dt['layout'],
            'template' => 'dynamic',
            'config' => 'default',
            'send_at' => new FrozenTime('now'),
            'format' => 'html',
            'from_name' => $dt['sender_name'],
            'from_email' => $dt['sender_email'],
          ];

          if (Configure::read('MailLogo')) {
            $options['attachments'] = $logoAttachment;
          }

          EmailQueue::enqueue($to, $data, $options);
        }
      }
      //Se ero in add faccio un redirect su edit
      if (empty($id)) {
        $this->redirect([$campaign->id, '?' => $this->request->getQuery()]);
      }
    }
    $this->set('campaign', $campaign);
    $this->set('count', $query->count());
    $this->set('ids', $ids);
  }

  /**
   * Delete method
   *
   * @param string|null $id Campaign id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $campaign = $this->Campaigns->get($id);
    if ($this->Campaigns->delete($campaign)) {
      $this->Flash->success(__('The campaign has been deleted.'));
    } else {
      $this->Flash->error(__('The campaign could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
