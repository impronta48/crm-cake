<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

/**
 * Users Model
 *
 * @property \App\Model\Table\CampaignsTable&\Cake\ORM\Association\HasMany $Campaigns
 * @property \App\Model\Table\CespiticalendarioTable&\Cake\ORM\Association\HasMany $Cespiticalendario
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\User> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\User> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Persone', [
            'foreignKey' => 'persona_id',
        ]);
        $this->hasMany('Campaigns', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Cespiticalendario', [
            'foreignKey' => 'user_id',
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
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 40)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('telegram_id')
            ->maxLength('telegram_id', 255)
            ->allowEmptyString('telegram_id');

        $validator
            ->integer('group_id')
            ->requirePresence('group_id', 'create')
            ->notEmptyString('group_id');

        $validator
            ->integer('persona_id')
            ->allowEmptyString('persona_id');

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
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->existsIn(['persona_id'], 'Persone'), ['errorField' => 'persona_id']);

        return $rules;
    }

    public function generateAuthToken($id)
    {
        //CakeLog::write('jwt_debug', "Sto generando jwt per " . $user_id);

        $privateKey = file_get_contents(ROOT . DS . 'config' . DS . 'jwtRS256.key');
        if ($privateKey === false) {
            //CakeLog::write('jwt_debug', "La private key non esiste!");
            return null;
        }
        $payload = [
            "sub" => $id,
        ];

        try {
            $jwt = JWT::encode($payload, $privateKey, 'RS256');
            //CakeLog::write('jwt_debug', "Jwt generato correttamente per " . $user_id);
            return $jwt;
        } catch (Exception $e) {
            //CakeLog::write('jwt_debug', serialize($e));
            return null;
        }
    }

    public function getUserIdFromAuthToken($jwt)
    {
        $publicKey = file_get_contents(ROOT . DS . 'config' . DS . 'jwtRS256.pem');
        if ($publicKey === false) {
            //CakeLog::write('jwt_debug', "La public key non esiste!");
            return null;
        }
        try {            
            JWT::$leeway = 60; // $leeway in seconds
            $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));

            //CakeLog::write('jwt_debug', serialize($decoded));
            return $decoded->sub;
        } catch (Exception $e) {
            //CakeLog::write('jwt_debug', serialize($e));
            return null;
        }
    }
}
