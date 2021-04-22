<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attivita Model
 *
 * @method \App\Model\Entity\Attivita newEmptyEntity()
 * @method \App\Model\Entity\Attivita newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Attivita[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attivita get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attivita findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Attivita patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attivita[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attivita|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attivita saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attivita[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Attivita[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Attivita[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Attivita[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttivitaTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('attivita');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Progetti', [
            'foreignKey' => 'progetto_id',
        ]);
        $this->belongsTo('Clienti', [
            'foreignKey' => 'cliente_id',
        ]);
        $this->belongsTo('Aree', [
            'foreignKey' => 'area_id',
        ]);
        $this->belongsTo('Aziende', [
            'foreignKey' => 'azienda_id',
        ]);
        $this->hasMany('Aliases', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Cespiticalendario', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Ddt', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Faseattivita', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Fattureemesse', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Fatturericevute', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Ordini', [
            'foreignKey' => 'attivita_id',
        ]);
        $this->hasMany('Primanota', [
            'foreignKey' => 'attivita_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->date('DataPresentazione')
            ->allowEmptyDate('DataPresentazione');

        $validator
            ->date('DataApprovazione')
            ->allowEmptyDate('DataApprovazione');

        $validator
            ->date('DataInizio')
            ->allowEmptyDate('DataInizio');

        $validator
            ->date('DataFine')
            ->allowEmptyDate('DataFine');

        $validator
            ->date('DataFinePrevista')
            ->allowEmptyDate('DataFinePrevista');

        $validator
            ->numeric('NumOre')
            ->allowEmptyString('NumOre');

        $validator
            ->integer('NumOreConsuntivo')
            ->allowEmptyString('NumOreConsuntivo');

        $validator
            ->decimal('OffertaAlCliente')
            ->allowEmptyString('OffertaAlCliente');

        $validator
            ->decimal('ImportoAcquisito')
            ->allowEmptyString('ImportoAcquisito');

        $validator
            ->decimal('NettoOra')
            ->allowEmptyString('NettoOra');

        $validator
            ->integer('OreUfficio')
            ->allowEmptyString('OreUfficio');

        $validator
            ->scalar('MotivazioneRit')
            ->maxLength('MotivazioneRit', 4294967295)
            ->allowEmptyString('MotivazioneRit');

        $validator
            ->numeric('Utile')
            ->allowEmptyString('Utile');

        $validator
            ->scalar('Note')
            ->maxLength('Note', 4294967295)
            ->allowEmptyString('Note');

        $validator
            ->boolean('chiusa')
            ->allowEmptyString('chiusa');

        $validator
            ->scalar('alias')
            ->allowEmptyString('alias');

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
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name']);
        $rules->add($rules->existsIn(['progetto_id'], 'Progetti'), ['errorField' => 'progetto_id']);
        $rules->add($rules->existsIn(['cliente_id'], 'Clienti'), ['errorField' => 'cliente_id']);
        $rules->add($rules->existsIn(['area_id'], 'Aree'), ['errorField' => 'area_id']);
        $rules->add($rules->existsIn(['azienda_id'], 'Aziende'), ['errorField' => 'azienda_id']);

        return $rules;
    }
}
