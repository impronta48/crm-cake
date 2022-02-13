<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Progetti Model
 *
 * @property \App\Model\Table\AreeTable&\Cake\ORM\Association\BelongsTo $Aree
 * @property \App\Model\Table\AttivitaTable&\Cake\ORM\Association\HasMany $Attivita
 *
 * @method \App\Model\Entity\Progetto newEmptyEntity()
 * @method \App\Model\Entity\Progetto newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Progetto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Progetto get($primaryKey, $options = [])
 * @method \App\Model\Entity\Progetto findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Progetto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Progetto[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Progetto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Progetto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Progetto[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Progetto[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Progetto[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Progetto[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ProgettiTable extends Table
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

        $this->setTable('progetti');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Aree', [
            'foreignKey' => 'area_id',
        ]);
        $this->hasMany('Attivita', [
            'foreignKey' => 'progetto_id',
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
            ->maxLength('name', 150)
            ->allowEmptyString('name');

        $validator
            ->allowEmptyString('DescrizioneProgetto');

        $validator
            ->numeric('PercentualeIVA')
            ->allowEmptyString('PercentualeIVA');

        $validator
            ->scalar('Nota')
            ->maxLength('Nota', 4294967295)
            ->allowEmptyString('Nota');

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
        $rules->add($rules->existsIn(['area_id'], 'Aree'), ['errorField' => 'area_id']);

        return $rules;
    }
}
