<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrenciesTableSeeder::class);
        $this->call(FiatCurrenciesTableSeeder::class);
        $this->call(MarketTableSeeder::class);
        $this->call(SiteSettingsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RoleAndPermissionsSeeder::class);
        $this->call(LocalesTableSeeder::class);

    }
}
