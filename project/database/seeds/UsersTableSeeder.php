<?php

use App\Jobs\Wallets\CreateUserFiatWallets;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    use \Illuminate\Foundation\Bus\DispatchesJobs;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('production')) {
            echo 'You can not run this seeder on production!'.PHP_EOL;

            return;
        }

        $this->resetData();

        //Create 100 users
        $i = 1;
        while ($i <= 10) {
            $data = [
                'email' => 'user'.$i.'@exbita.com',
                'name' => 'Test User '.$i,
            ];

            $user = factory(\App\User::class)->create($data);

            //make sure fiat wallets are created instantly
            $this->dispatchNow(new CreateUserFiatWallets($user));
            //Create a BTC wallet with dummy address
            $user->createTestWallet(1);
            $i++;
        }
    }

    public function resetData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE oauth_access_tokens');
        DB::statement('TRUNCATE oauth_refresh_tokens');
        DB::statement('TRUNCATE users');
        DB::statement('TRUNCATE login_attempts');
        DB::statement('TRUNCATE fiat_wallets');
        DB::statement('TRUNCATE wallets');
        DB::statement('TRUNCATE sms_logs');
        DB::statement('TRUNCATE orders');
        DB::statement('TRUNCATE transactions');
        DB::statement('TRUNCATE wallet_addresses');
        DB::statement('TRUNCATE user_id_documents');
        DB::statement('TRUNCATE user_allowed_ips');
        DB::statement('TRUNCATE attachments');
        DB::statement('TRUNCATE fiat_deposits');
        DB::statement('TRUNCATE fiat_withdrawal');
        DB::statement('TRUNCATE fiat_withdrawal');
        DB::statement('TRUNCATE withdrawal');
        DB::statement('TRUNCATE tx_btc');
        DB::statement('TRUNCATE jobs');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
