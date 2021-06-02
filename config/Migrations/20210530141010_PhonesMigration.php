<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class PhonesMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('phones', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'signed' => false,
            'identity' => true,
        ]);
        $table->addColumn('user_id', 'integer', [
            'signed' => false,
        ]);
        $table->addColumn('phone_code', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('phone_number', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('full_phone', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('is_phone_verified', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->addColumn('phone_verification_code', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('is_default', 'boolean', [
            'default' => false,
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
