<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class MessagesMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('messages', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'signed' => false,
            'identity' => true,
        ]);
        $table->addColumn('user_id', 'integer', [
            'signed' => false,
        ]);
        $table->addColumn('subject', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('content', 'text', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('channel', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('address', 'string', [
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
