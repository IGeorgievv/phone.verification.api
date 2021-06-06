<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterUsersAddPassword extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('password', 'string', ['after' => 'email'])
              ->save();
    }

    public function down()
    {
        $table = $this->table('users');
        $table->removeColumn('password')
              ->save();
    }
}
