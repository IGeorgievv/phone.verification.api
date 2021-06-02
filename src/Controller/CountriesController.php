<?php
declare(strict_types=1);

namespace App\Controller;

class CountriesController extends DataController
{
    public function show(): void
    {
        $this->service('CountriesService', 'show');
    }

    public function index(): void
    {
        $this->service('CountriesService', 'index');
    }
}