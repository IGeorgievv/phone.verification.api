<?php
declare(strict_types=1);

namespace App\Controller;

use App\Error\JsonValidationException;

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

    public function verification(): void
    {
        $this->service('UsersService', 'verification');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'unauthorized', 'create']);
    }

    // /**
    //  * Guest session
    //  *
    //  * @param [type] $conditions
    //  * @param [type] $fields
    //  * @param [type] $order
    //  * @param boolean $recursive
    //  * @return void
    //  */
    // public function find($conditions, $fields, $order=null, $recursive=false)
    // {
    //     $user = parent::find($conditions, $fields, $order, $recursive);
    //     if(empty($user)) {
    //         // No "real" user found, let's create "guest"
    //         $user = array(
    //             'User' => array(
    //                 'id' => 0,
    //                 'username' => 'guest'
    //                 // add other fields as needed
    //             )
    //         );
    //     }
    //     return $user;
    // }

    public function login()
    {
        $this->service('UsersService', 'login', [
            'request' => $this->request,
            'Authentication' => $this->Authentication,
        ]);
    }

    public function unauthorized()
    {
        $this->service('UsersService', 'unauthorized');
    }

    // in src/Controller/UsersController.php
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();
            // return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            return [];
        }
    }
}