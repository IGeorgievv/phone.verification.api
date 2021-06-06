<?php
declare(strict_types=1);

namespace App\Services;

use App\Error\JsonValidationException;
use App\Model\Table\MessagesTable;
use App\Model\Table\PhonesTable;
use Cake\Http\Exception\ConflictException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhonesService extends BaseService
{
    public function create(array $params): array
    {
        if (!isset($params['number']) || trim($params['number']) == '' || !isset($params['country_code'])) {
            $errors['number']['_required'] = 'Required field!';
        } else {
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $numberProto = $phoneUtil->parse($params['number'], $params['country_code']);
                $isValidPhone = $phoneUtil->isValidNumber($numberProto);
            } catch (NumberParseException $e) {
                $errors['number']['_valid'] = 'Invalid phone number!';
            }

            if (!$isValidPhone) {
                $errors['number']['_valid'] = 'Invalid phone number!';
            }
        }

        if (count($errors) >= 1) {
            throw new JsonValidationException('The provided data is invalid for creation of user.', $errors);
        }

        $phonesTable = new PhonesTable();
        $phone = $phonesTable->newEntity([
            'user_id' => $this->session->id,
            'country_code' => $params['country_code'],
            'number' => $params['number'],
            'formatted' => $phoneUtil->format($numberProto, PhoneNumberFormat::E164),
            'verification_code' => strtoupper(substr(md5(microtime()), rand(0,26), 6)),
        ]);

        if (!$phonesTable->save($phone)) {
            if (count($phone->getErrors()) >= 1) {
                throw new JsonValidationException('The provided data is invalid for creation of user.', $phone->getErrors());
            }
            throw new ConflictException('The operation has failed!');
        }
        self::$models[] = [
            'handler' => $phonesTable,
            'entity' => $phone,
        ];
        (new MessagesService($this->session))->create([
            'user_id' => $this->session->id,
            'channel' => MessagesTable::COMMUNICATION_CHANNEL_PHONE,
            'address' => $phone->formatted .'@vtext.com',
            'subject' => null,
            'content' => 'Your code is - '. $phone->verification_code,
        ]);

        return $phone->toArray();
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
        $channels = new PhonesTable();
        $channelRecords = $channels->find(
            'all',
            ['conditions' => [
                'user_id' => $this->session['user']['id'],
                'is_verified' => 0,
            ]]
        );

        return $channelRecords->toArray();
    }

    public function destroy(array $params): array
    {
        return $params;
    }

    public function verification(array $params): array
    {
        $channels = new PhonesTable();
        $channel = $channels->find(
            'all',
            ['conditions' => [
                'user_id' => $this->session['user']['id'],
                'id' => $params['channel_id'],
                'is_verified' => false,
            ]]
        )->first();

        if (!isset($channel)) {
            throw new ConflictException('Missing unverified phone!');
        }

        (new VerificationLogService($this->session))->create([
            'communication_channel_type' => $params['channel_type'],
            'communication_channel_id' => $channel->id,
        ]);
        if ($channel->verification_code != $params['verification_code']) {
            throw new JsonValidationException('Unsuccessful verification.', [
                'verification_code' => ['_invalid' => 'Invalid code!'],
            ]);
        }

        $channel->set([
            'is_verified' => true,
        ]);
        $channels->save($channel);
        // if (!$channels->save($channel)) {
        //     if (count($channel->getErrors()) >= 1) {
        //         throw new JsonValidationException('The provided data is invalid for verification.', $channel->getErrors());
        //     }
        //     throw new ConflictException('The operation has failed!');
        // }
        $this->models[] = [
            'handler' => $channels,
            'id' => $channel->id,
        ];

        (new MessagesService())->create([
            'user_id' => $this->session['user']['id'],
            'channel' => MessagesTable::COMMUNICATION_CHANNEL_PHONE,
            'address' => $channel->formatted .'@vtext.com',
            'subject' => null,
            'content' => 'Welcome to SMSBump!',
        ]);

        return $channel->toArray();
    }
}