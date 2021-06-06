<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class VerificationLogsMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('verification_logs', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'signed' => false,
            'identity' => true,
        ]);
        $table->addColumn('user_id', 'integer', [
            'signed' => false,
        ]);
        $table->addColumn('communication_channel_id', 'integer', [
            'signed' => false,
        ]);
        $table->addColumn('communication_channel_type', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('attempts', 'integer');
        $table->addColumn('session_id', 'string', [
            'default' => null,
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
