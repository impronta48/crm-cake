<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Persone Model
 *
 * @method \App\Model\Entity\Persone newEmptyEntity()
 * @method \App\Model\Entity\Persone newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Persone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Persone get($primaryKey, $options = [])
 * @method \App\Model\Entity\Persone findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Persone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Persone[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Persone|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Persone saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Persone[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persone[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persone[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Persone[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PersoneTable extends Table
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

    $this->setTable('persone');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');

    $this->addBehavior('Timestamp');
    $this->addBehavior('Tags.Tag', [
      'taggedCounter' => false,
      'field' => 'tag_list',

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
      ->scalar('Nome')
      ->maxLength('Nome', 50)
      ->allowEmptyString('Nome');

    $validator
      ->scalar('Cognome')
      ->maxLength('Cognome', 75)
      ->allowEmptyString('Cognome');

    $validator
      ->scalar('Indirizzo')
      ->allowEmptyString('Indirizzo');

    $validator
      ->scalar('Citta')
      ->maxLength('Citta', 100)
      ->allowEmptyString('Citta');

    $validator
      ->scalar('Provincia')
      ->maxLength('Provincia', 10)
      ->allowEmptyString('Provincia');

    $validator
      ->scalar('Nazione')
      ->maxLength('Nazione', 50)
      ->allowEmptyString('Nazione');

    $validator
      ->scalar('CAP')
      ->maxLength('CAP', 5)
      ->allowEmptyString('CAP');

    $validator
      ->scalar('TelefonoDomicilio')
      ->maxLength('TelefonoDomicilio', 50)
      ->allowEmptyString('TelefonoDomicilio');

    $validator
      ->scalar('TelefonoUfficio')
      ->maxLength('TelefonoUfficio', 50)
      ->allowEmptyString('TelefonoUfficio');

    $validator
      ->date('DataDiNascita')
      ->allowEmptyDate('DataDiNascita');

    $validator
      ->dateTime('UltimoContatto')
      ->allowEmptyDateTime('UltimoContatto');

    $validator
      ->scalar('Nota')
      ->maxLength('Nota', 4294967295)
      ->allowEmptyString('Nota');

    $validator
      ->scalar('Titolo')
      ->maxLength('Titolo', 50)
      ->allowEmptyString('Titolo');

    $validator
      ->scalar('Carica')
      ->maxLength('Carica', 100)
      ->allowEmptyString('Carica');

    $validator
      ->scalar('Societa')
      ->maxLength('Societa', 255)
      ->allowEmptyString('Societa');

    $validator
      ->scalar('SitoWeb')
      ->maxLength('SitoWeb', 255)
      ->allowEmptyString('SitoWeb');

    $validator
      ->scalar('ModificatoDa')
      ->maxLength('ModificatoDa', 50)
      ->allowEmptyString('ModificatoDa');

    $validator
      ->scalar('EMail')
      ->maxLength('EMail', 150)
      ->allowEmptyString('EMail');

    $validator
      ->scalar('Fax')
      ->maxLength('Fax', 50)
      ->allowEmptyString('Fax');

    $validator
      ->scalar('Cellulare')
      ->maxLength('Cellulare', 50)
      ->allowEmptyString('Cellulare');

    $validator
      ->scalar('IM')
      ->maxLength('IM', 150)
      ->allowEmptyString('IM');

    $validator
      ->scalar('Categorie')
      ->maxLength('Categorie', 255)
      ->allowEmptyString('Categorie');

    $validator
      ->scalar('DisplayName')
      ->maxLength('DisplayName', 255)
      ->allowEmptyString('DisplayName');

    $validator
      ->scalar('piva')
      ->maxLength('piva', 20)
      ->allowEmptyString('piva');

    $validator
      ->scalar('cf')
      ->maxLength('cf', 16)
      ->allowEmptyString('cf');

    $validator
      ->scalar('iban')
      ->maxLength('iban', 30)
      ->allowEmptyString('iban');

    $validator
      ->scalar('NomeBanca')
      ->maxLength('NomeBanca', 50)
      ->allowEmptyString('NomeBanca');

    $validator
      ->scalar('altroIndirizzo')
      ->allowEmptyString('altroIndirizzo');

    $validator
      ->scalar('altraCitta')
      ->maxLength('altraCitta', 50)
      ->allowEmptyString('altraCitta');

    $validator
      ->scalar('altroCap')
      ->maxLength('altroCap', 5)
      ->allowEmptyString('altroCap');

    $validator
      ->scalar('altraProv')
      ->maxLength('altraProv', 2)
      ->allowEmptyString('altraProv');

    $validator
      ->scalar('altraNazione')
      ->maxLength('altraNazione', 2)
      ->allowEmptyString('altraNazione');

    $validator
      ->boolean('EntePubblico')
      ->allowEmptyString('EntePubblico');

    $validator
      ->scalar('codiceIPA')
      ->maxLength('codiceIPA', 255)
      ->allowEmptyString('codiceIPA');

    $validator
      ->scalar('indirizzoPEC')
      ->requirePresence('indirizzoPEC', 'create')
      ->allowEmptyString('indirizzoPEC');

    return $validator;
  }
}
