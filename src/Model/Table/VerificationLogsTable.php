<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VerificationLogs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CommunicationChannelsTable&\Cake\ORM\Association\BelongsTo $CommunicationChannels
 * @property \App\Model\Table\SessionsTable&\Cake\ORM\Association\BelongsTo $Sessions
 *
 * @method \App\Model\Entity\VerificationLog newEmptyEntity()
 * @method \App\Model\Entity\VerificationLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\VerificationLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VerificationLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\VerificationLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\VerificationLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VerificationLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VerificationLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VerificationLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VerificationLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VerificationLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\VerificationLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\VerificationLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VerificationLogsTable extends Table
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

        $this->setTable('verification_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('CommunicationChannels', [
            'foreignKey' => 'communication_channel_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Sessions', [
            'foreignKey' => 'session_id',
            'joinType' => 'INNER',
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('communication_channel_type')
            ->maxLength('communication_channel_type', 255)
            ->requirePresence('communication_channel_type', 'create')
            ->notEmptyString('communication_channel_type');

        $validator
            ->integer('attempts')
            ->requirePresence('attempts', 'create')
            ->notEmptyString('attempts');

        $validator
            ->scalar('device')
            ->maxLength('device', 255)
            ->requirePresence('device', 'create')
            ->notEmptyString('device');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->requirePresence('updated_at', 'create')
            ->notEmptyDateTime('updated_at');

        $validator
            ->dateTime('deleted_at')
            ->allowEmptyDateTime('deleted_at');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['communication_channel_id'], 'CommunicationChannels'), ['errorField' => 'communication_channel_id']);
        $rules->add($rules->existsIn(['session_id'], 'Sessions'), ['errorField' => 'session_id']);

        return $rules;
    }
}
