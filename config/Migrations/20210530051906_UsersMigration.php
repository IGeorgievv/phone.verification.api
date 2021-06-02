<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class UsersMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'signed' => false,
            'identity' => true,
        ]);
        $table->addColumn('type', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('updated_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('deleted_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addPrimaryKey(['id']);
        $table->create();
    }
}
