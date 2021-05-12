<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE locales');

        $data = [
            'id' => 1,
            'locale' => 'en',
            'name' => 'English',
            'is_active' => 1,
            'terms' => file_get_contents(resource_path('static-content/terms/en.md')),
            'apidocs' => file_get_contents(resource_path('static-content/apidocs/en.md')),

        ];
        $market = new \App\Models\Locale($data);
        $market->save();



        $data = [
            'id' => 2,
            'locale' => 'tr',
            'name' => 'Turkish',
            'is_active' => 1,
            'terms' => file_get_contents(resource_path('static-content/terms/tr.md')),
            'apidocs' => file_get_contents(resource_path('static-content/apidocs/tr.md')),

        ];

        $market = new \App\Models\Locale($data);
        $market->save();
    }
}
