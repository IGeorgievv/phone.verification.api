<?php
declare(strict_types=1);

namespace App\Services;

use App\Error\JsonValidationException;
use App\Model\Table\MessagesTable;
use App\Model\Table\PhonesTable;
use App\Model\Table\UsersTable;
use App\Model\Table\VerificationLogsTable;
use Authentication\Authenticator\JwtAuthenticator;
use Brick\VarExporter\Internal\ObjectExporter\SetStateExporter;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use JsonSchema\Exception\ValidationException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class UsersService extends BaseService
{
    public function create(array $params): array
    {
        $usersTable = new UsersTable();
        $params['type'] = 'client';

        $phoneData = $params['phone'];
        unset($params['phone']);
        $user = $usersTable->newEntity($params);
        $errors = $user->getErrors();
        if (!preg_match('#[0-9]+#', $params['password'])) {
            $errors['password']['contain_number'] = 'Must contain at least 1 number!';
        }
        if (!preg_match('#[A-Z]+#', $params['password'])) {
            $errors['password']['contain_capital_letter'] = 'Must contain at least 1 capital letter!';
        }
        if (!preg_match('#[a-z]+#', $params['password'])) {
            $errors['password']['contain_lowercase_letter'] = 'Must contain at least 1 lowercase letter!';
        }

        if (!isset($phoneData['number']) || trim($phoneData['number']) == '' || !isset($phoneData['country_code'])) {
            $errors['phone']['number']['_required'] = 'Required field!';
        } else {
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $numberProto = $phoneUtil->parse($phoneData['number'], $phoneData['country_code']);
                $isValidPhone = $phoneUtil->isValidNumber($numberProto);
            } catch (NumberParseException $e) {
                $errors['phone']['number']['_valid'] = 'Invalid phone number!';
            }

            if (!$isValidPhone) {
                $errors['phone']['number']['_valid'] = 'Invalid phone number!';
            } else {
                // $phone->phone_number = $params['phone_number'];
                // $phone->phone_code = $params['phone_code'];
                // $phone->full_phone = $phoneUtil->format($numberProto, PhoneNumberFormat::E164);;
            }
        }

        if (count($errors) >= 1) {
            throw new JsonValidationException('The provided data is invalid for creation of user.', $errors);
        }

        if (!$usersTable->save($user)) {
            if (count($user->getErrors()) >= 1) {
                throw new JsonValidationException('The provided data is invalid for creation of user.', $user->getErrors());
            }
            throw new ConflictException('The operation has failed!');
        }

        self::$models[] = [
            'handler' => $usersTable,
            'entity' => $user,
        ];

        $phonesTable = new PhonesTable();
        $phone = $phonesTable->newEntity([
            'user_id' => $user->id,
            'country_code' => $phoneData['country_code'],
            'number' => $phoneData['number'],
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
            'user_id' => $user->id,
            'channel' => MessagesTable::COMMUNICATION_CHANNEL_PHONE,
            'address' => $phone->formatted .'@vtext.com',
            'subject' => null,
            'content' => 'Your code is - '. $phone->verification_code,
        ]);

        $response = $user->toArray();
        $response['phone'] = $phone->toArray();

        return $response;
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

    public function unauthorized(array $params): array
    {
        throw new UnauthorizedException('Please, log in to continue!');

        return [];
    }

    public function login(array $params): array
    {
        $this->request->allowMethod(['post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $privateKey = file_get_contents(__DIR__ .'/../../config/jwt.key');
            $user = $result->getData();
            $payload = [
                'iss' => 'sms',
                'sub' => $user->id,
                'exp' => time() + 604800,
            ];

            return [
                'token' => JWT::encode($payload, $privateKey, 'RS256'),
                'user' => $user->toArray(),
            ];
        }

        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            throw new JsonValidationException('The provided data is invalid for login.', [
                'email' => ['_login' => 'Invalid username or password!'],
            ]);
        }
    }
}