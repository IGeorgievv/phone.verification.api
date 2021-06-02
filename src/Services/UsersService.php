<?php
declare(strict_types=1);

namespace App\Services;

use App\Error\JsonValidationException;
use App\Model\Table\UsersTable;
use Cake\I18n\Time;
use JsonSchema\Exception\ValidationException;

class UsersService
{
    public function create(array $params): array
    {
        $user = new UsersTable();
        $time = Time::now();
        $params['type'] = 'client';
        $params['created_at'] = $time;
        $params['updated_at'] = $params['created_at'];

        $user = $user->newEntity($params);
        $errors = $user->getErrors();
        if (!preg_match('#[0-9]+#',$params['password'])) {
            $errors['password']['contain_number'] = 'Must contain at least 1 number!';
        }
        if (!preg_match('#[A-Z]+#',$params['password'])) {
            $errors['password']['contain_capital_letter'] = 'Must contain at least 1 capital letter!';
        }
        if (!preg_match('#[a-z]+#',$params['password'])) {
            $errors['password']['contain_lowercase_letter'] = 'Must contain at least 1 lowercase letter!';
        }
        
        if (count($errors) >= 1) {
            throw new JsonValidationException('The provided data is invalid for creation of user.', $errors);
        }
        
        return $params;
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