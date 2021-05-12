<?php

use App\Jobs\Wallets\CreateUserFiatWallets;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    use \Illuminate\Foundation\Bus\DispatchesJobs;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demoAdminUser = ['Admin Demo', 'demo_admin@exbita.com', 'demo_admin'];
        $demoUser = ['User Demo', 'demo_user@exbita.com', 'demo_user'];

        // ADMIN DEMO USER

        $data = [
            'name' => $demoAdminUser[0],
            'email' => $demoAdminUser[1],
            'password' => Hash::make($demoAdminUser[2]),
            'status' => 1,
            'id_verified' => 1,
        ];

        $admin = User::create($data);

        if ($admin->fiatWallets->isEmpty()) {
            $this->dispatchNow(new CreateUserFiatWallets($admin))->onQueue('wallets');
        }
        if ($admin->wallets->isEmpty()) {
            $admin->createTestWallet(\App\Models\Currency::ID_BTC);
        }

        $admin->removeRoles($admin->roles);

        $admin->assignRoles('admin');

        // Admin Access
        $admin->givePermissionTo('access.admin');


        // DEMO USER

        $data = [
            'name' => $demoUser[0],
            'email' => $demoUser[1],
            'password' => Hash::make($demoUser[2]),
            'status' => 1,
            'id_verified' => 1,
        ];

        $user = User::create($data);

        //make sure fiat wallets are created instantly
        $this->dispatchNow(new CreateUserFiatWallets($user))->onQueue('wallets');
        //Create a BTC wallet with dummy address

        if ($user->wallets->isEmpty()) {
            $user->createTestWallet(\App\Models\Currency::ID_BTC);
        }
    }
}
