<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Countries seed.
 */
class CountriesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $countriesFile = file_get_contents(__DIR__ .'/../../resources/Countries.json');
        $countries = json_decode($countriesFile, true);
        $data = [];

        foreach ($countries as $country) {
            $data[] = [
                'name' => $country['name'],
                'iso_code' => $country['isoCode'],
                'phone_code' => $country['dialCode'],
                'flag_url' => $country['flag'],
            ];
        }

        $table = $this->table('countries');
        $table->insert($data)->save();
    }
}
