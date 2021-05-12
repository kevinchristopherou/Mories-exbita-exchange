<?php

use Illuminate\Database\Seeder;

class DatabaseTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(SiteSettingsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RoleAndPermissionsSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(FiatCurrenciesTableSeeder::class);
        $this->call(MarketTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(OrderTableSeeder::class);
    }
}
