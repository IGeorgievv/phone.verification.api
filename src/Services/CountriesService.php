<?php
declare(strict_types=1);

namespace App\Services;

use App\Model\Table\CountriesTable;

class CountriesService
{
    public function show(array $params): array
    {
        $countries = new CountriesTable;
        $country = $countries->find(
            'all',
            ['conditions' => ['iso_code' => $params['iso_code'],]]
        )->first()->toArray();

        return $country;
    }

    public function index(array $params): array
    {
        $countries = new CountriesTable;

        return $countries->find('all')->toArray();
    }
}