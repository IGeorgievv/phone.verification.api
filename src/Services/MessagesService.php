<?php
declare(strict_types=1);

namespace App\Services;

use App\Error\JsonValidationException;
use App\Model\Table\MessagesTable;
use Cake\Http\Exception\ConflictException;

class MessagesService extends BaseService
{
    public function create(array $params): array
    {
        $messagesTable = new MessagesTable();

        $message = $messagesTable->newEntity([
            'user_id' => $params['user_id'],
            'channel' => $params['channel'],
            'subject' => $params['subject'],
            'content' => $params['content'],
            'address' => $params['address'],
        ]);

        if (count($message->getErrors()) >= 1) {
            throw new JsonValidationException(
                'The provided data is invalid for creation of message.',
                $message->getErrors()
            );
        }

        if (!$messagesTable->save($message)) {
            if (count($message->getErrors()) >= 1) {
                throw new JsonValidationException(
                    'The provided data is invalid for creation of message.',
                    $message->getErrors()
                );
            }

            throw new ConflictException('The operation has failed!');
        }

        self::$models[] = [
            'handler' => $messagesTable,
            'entity' => $message,
        ];

        /**
         * Send the message
         */

        return $message->toArray();
    }

    public function show(array $params): array
    {
        return $params;
    }

    public function update(array $params): array
    {
        return $params;
    }

    public function index(array $params): array
    {
        return $params;
    }

    public function destroy(array $params): array
    {
        return $params;
    }
}