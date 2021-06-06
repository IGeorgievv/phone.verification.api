<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\JsonValidationException;
use App\Model\Table\MessagesTable;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validator;

class CommunicationChannelsController extends DataController
{
    public function create(): void
    {
        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'create');
                break;
            default:
                throw new NotFoundException();
        }
    }

    public function show(): void
    {
        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'show');
                break;
            default:
                throw new NotFoundException();
        }
    }

    public function update(): void
    {
        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'update');
                break;
            default:
                throw new NotFoundException();
        }
    }

    public function index(): void
    {
        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'index');
                break;
            default:
                throw new NotFoundException();
        }
    }

    public function destroy(): void
    {
        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'destroy');
                break;
            default:
                throw new NotFoundException();
        }
    }

    public function verification(): void
    {
        $validator = new Validator();
        $validator
            ->requirePresence('channel_id')
            ->integer('channel_id')
            ->requirePresence('verification_code')
            ->notEmpty('verification_code', 'Required by the operation!');

        $errors = $validator->validate($this->request->getData());

        switch ($this->_getCleanParams()['channel_type']) {
            case MessagesTable::COMMUNICATION_CHANNEL_PHONE .'s':
                $this->service('PhonesService', 'verification', $errors);
                break;
            default:
                throw new NotFoundException();
        }
    }
}