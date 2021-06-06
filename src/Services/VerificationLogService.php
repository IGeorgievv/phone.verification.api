<?php
declare(strict_types=1);

namespace App\Services;

use App\Error\JsonValidationException;
use App\Model\Table\VerificationLogsTable;
use Cake\Http\Exception\ConflictException;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;

class VerificationLogService extends BaseService
{
    /**
     * Creates verification log
     *
     * @param array $params - communication_channel_id, communication_channel_type / attempts, session_id, user_id
     * @return array
     */
    public function create(array $params): array
    {
        $verificationLogsTable = new VerificationLogsTable();
        $verificationLog = $verificationLogsTable->newEntity([
            'communication_channel_type' => $params['communication_channel_type'],
            'communication_channel_id' => $params['communication_channel_id'],
            'session_id' => $this->session['user']['id'],
            'user_id' => $this->session['user']['id'],
            'attempts' => $this->_getAttempt($params['communication_channel_type'], $params['communication_channel_id']),
        ]);

        if (count($verificationLog->getErrors()) >= 1) {
            throw new JsonValidationException(
                'The provided data is invalid for creation of message.',
                $verificationLog->getErrors()
            );
        }

        if (!$verificationLogsTable->save($verificationLog)) {
            if (count($verificationLog->getErrors()) >= 1) {
                throw new JsonValidationException(
                    'The provided data is invalid for creation of message.',
                    $verificationLog->getErrors()
                );
            }
            throw new ConflictException('The operation has failed!');
        }

        // self::$models[] = [
        //     'handler' => $verificationLogsTable,
        //     'entity' => $verificationLog,
        // ];

        return $verificationLog->toArray();
    }

    private function _getAttempt(string $channel_type, int $channel_id): int
    {
        $logs = new VerificationLogsTable;
        $log = $logs->find(
            'all',
            ['conditions' => [
                'communication_channel_type' => $channel_type,
                'communication_channel_id' => $channel_id,
                'user_id' => $this->session['user']['id'],
            ]]
        )->last();
        if (!isset($log)) {
            return 1;
        }

        $now = FrozenTime::now();
        $lastLog = (new FrozenTime($log->created_at))->addMinute(1);
        if ($now <= $lastLog) {
            if ($log->attempts == 3) {
                throw new ConflictException('Wait for a minute and try again!');
            }

            return ++$log->attempts;
        }

        return 1;
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