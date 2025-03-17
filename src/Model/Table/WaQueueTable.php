<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WaQueue Model
 *
 * @property \App\Model\Table\CampaignsTable&\Cake\ORM\Association\BelongsTo $Campaigns
 *
 * @method \App\Model\Entity\WaQueue newEmptyEntity()
 * @method \App\Model\Entity\WaQueue newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WaQueue> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WaQueue get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WaQueue findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WaQueue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WaQueue> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WaQueue|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WaQueue saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WaQueue>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WaQueue>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WaQueue>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WaQueue> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WaQueue>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WaQueue>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WaQueue>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WaQueue> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WaQueueTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('wa_queue');
        $this->setDisplayField('phone');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Persone', [
            'foreignKey' => 'persona_id',
        ]);
        $this->belongsTo('Campaigns', [
            'foreignKey' => 'campaign_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

        $validator
            ->integer('persona_id')
            ->allowEmptyString('persona_id');

        $validator
            ->scalar('wa_session')
            ->maxLength('wa_session', 255)
            ->requirePresence('wa_session', 'create')
            ->notEmptyString('wa_session');

        $validator
            ->scalar('from_name')
            ->maxLength('from_name', 255)
            ->allowEmptyString('from_name');

        $validator
            ->scalar('config')
            ->maxLength('config', 255)
            ->allowEmptyString('config');

        $validator
            ->scalar('template')
            ->maxLength('template', 255)
            ->allowEmptyString('template');

        $validator
            ->scalar('layout')
            ->maxLength('layout', 255)
            ->allowEmptyString('layout');

        $validator
            ->scalar('body')
            ->maxLength('body', 255)
            ->allowEmptyString('body');

        $validator
            ->boolean('sent')
            ->notEmptyString('sent');

        $validator
            ->boolean('locked')
            ->notEmptyString('locked');

        $validator
            ->integer('send_tries')
            ->notEmptyString('send_tries');

        $validator
            ->dateTime('send_at')
            ->allowEmptyDateTime('send_at');

        $validator
            ->scalar('error')
            ->maxLength('error', 255)
            ->allowEmptyString('error');

        $validator
            ->integer('campaign_id')
            ->allowEmptyString('campaign_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['persona_id'], 'Persone'), ['errorField' => 'persona_id']);
        $rules->add($rules->existsIn(['campaign_id'], 'Campaigns'), ['errorField' => 'campaign_id']);

        return $rules;
    }

    public function enqueue($data): bool
    {  
      $defaults = [
        'send_at' => \Cake\I18n\DateTime::now(),
        'template' => 'default',
        'layout' => 'default',
        'config' => 'default',
        'campaign_id' => 0,
      ];
  
      $wamessage = $data + $defaults;  
      $wamessages = [$wamessage];
      $entity = $this->newEntities($wamessages);
  
      return $this->getConnection()->transactional(function () use ($entity) {
        $failure = collection($entity)
          ->map(function ($wamessage) {
            return $this->save($wamessage);
          })
          ->contains(false);
  
        return !$failure;
      });
    }


  /**
   * Returns a list of queued wa messages that needs to be sent.
   *
   * @param int|string $size number of unset wa messages to return
   * @throws \Exception any exception raised in transactional callback
   * @return array list of unsent wa messages
   */
  public function getBatch($size = 10): array
  {
    return $this->getConnection()->transactional(function () use ($size) {
      $emails = $this->find()
        ->where([
          $this->aliasField('sent') => false,
          $this->aliasField('send_tries') . ' <=' => 3,
          $this->aliasField('send_at') . ' <=' => \Cake\I18n\DateTime::now(),
          $this->aliasField('locked') => false,
        ])
        ->limit($size)
        ->order([$this->aliasField('created') => 'ASC']);

      $emails
          ->func(function (\Cake\Collection\CollectionInterface $ids) {
          if (!$ids->isEmpty()) {
            $this->updateAll(['locked' => true], ['id IN' => $ids->toList()]);
          }

          return $ids;
        });
      
      return $emails->toArray();
    });
  }

  /**
   * Releases locks for all emails in $ids.
   *
   * @param array|\Traversable $ids The email ids to unlock
   *
   * @return void
   */
  public function releaseLocks($ids): void
  {
    $this->updateAll(['locked' => false], ['id IN' => $ids]);
  }

  /**
   * Releases locks for all emails in queue, useful for recovering from crashes.
   *
   * @return void
   */
  public function clearLocks(): void
  {
    $this->updateAll(['locked' => false], '1=1');
  }

  /**
   * Marks an email from the queue as sent.
   *
   * @param string $id queued email id
   * @return void
   */
  public function success($id): void
  {
    $this->updateAll(['sent' => true], ['id' => $id]);
  }

  /**
   * Marks an email from the queue as failed, and increments the number of tries.
   *
   * @param string $id queued email id
   * @param string $error message
   * @return void
   */
  public function fail($id, $error = null): void
  {
    $this->updateAll(
      [
        'send_tries' => new QueryExpression('send_tries + 1'),
        'error' => $error,
      ],
      [
        'id' => $id,
      ]
    );
  }

//   /**
//    * Sets the column type for template_vars and headers to json.
//    *
//    * @param \Cake\Database\Schema\TableSchemaInterface $schema The table description
//    * @return \Cake\Database\Schema\TableSchema
//    */
//   protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
//   {
//     $type = Configure::read('EmailQueue.serialization_type') ?: 'email_queue.serialize';
//     $schema->setColumnType('template_vars', $type);
//     $schema->setColumnType('headers', $type);
//     $schema->setColumnType('attachments', $type);

//     return $schema;
//   }

  public function status($campaign_id = null): Query
  {
    //Cerco nella mailqueue tutti i destinatari di quella campagna e il loro stato
    $query = $this->find()
      ->select(['email', 'sent', 'send_tries', 'send_at', 'locked', 'error']);

    if (!empty($campaign_id)) {
      $query->where(['campaign_id' => $campaign_id]);
    }

    return $query;
  }
}
