<?php

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('TRUNCATE settings');

        $settings = [
            [
                'key' => 'SITE_COMMISSION',
                'value' => 0.25,
            ],
            [
                'key' => 'SITE_COMMISSION_TAX',
                'value' => 0.18,
            ],
            [
                'key' => 'SITE_REFERRAL_COMMISSION',
                'value' => 0.50,
            ],
            [
                'key' => 'QUICK_ORDER_STOP_GAP_PERCENTAGE',
                'value' => 5,
            ],
        ];

        Settings::insert($settings);
    }
}
