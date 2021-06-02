<?php
declare(strict_types=1);

namespace App\Controller;

class UsersController extends DataController
{
    public function create(): void
    {
        $this->service('UsersService', 'create');
    }

    public function show(): void
    {
        $this->service('UsersService', 'show');
    }

    public function update(): void
    {
        $this->service('UsersService', 'update');
    }

    public function index(): void
    {
        $this->service('UsersService', 'index');
    }

    public function destroy(): void
    {
        $this->service('UsersService', 'destroy');
    }
}