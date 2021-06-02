<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCountries extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('countries', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'integer', [
            'autoIncrement' => true,
            'signed' => false,
            'identity' => true,
        ]);
        $table->addColumn('name', 'string');
        $table->addColumn('iso_code', 'string');
        $table->addColumn('phone_code', 'string');
        $table->addColumn('flag_url', 'string');
        $table->addPrimaryKey(['id']);
        $table->addIndex(['iso_code'], ['unique' => true]);
        $table->create();
    }
}
