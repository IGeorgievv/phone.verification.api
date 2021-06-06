<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterPhonesClearNaming extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('phones');
        $table->renameColumn('phone_code', 'country_code')
              ->renameColumn('phone_number', 'number')
              ->renameColumn('full_phone', 'formatted')
              ->renameColumn('is_phone_verified', 'is_verified')
              ->renameColumn('phone_verification_code', 'verification_code')
              ->save();
    }

    public function down()
    {
        $table = $this->table('phones');
        $table->renameColumn('country_code', 'phone_code')
              ->renameColumn('number', 'phone_number')
              ->renameColumn('formatted', 'full_phone')
              ->renameColumn('is_verified', 'is_phone_verified')
              ->renameColumn('verification_code', 'phone_verification_code')
              ->save();
    }
}
