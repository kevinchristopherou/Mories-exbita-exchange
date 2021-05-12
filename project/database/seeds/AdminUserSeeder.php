<?php

use App\Jobs\Wallets\CreateUserFiatWallets;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use \Illuminate\Foundation\Bus\DispatchesJobs;

    public $email = 'giraytemel@gmail.com';

    public $name = 'Giray Temel';

    public $pass = 'gite123!';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('email', $this->email)->first();

        if (! $admin) {
            $data = [
                'email' => $this->email,
                'name' => $this->name,
                'password' => Hash::make($this->pass),
                'status' => 1,
                'id_verified' => 1,
            ];

            $admin = User::create($data);
        }

        if ($admin->fiatWallets->isEmpty()) {
            $this->dispatchNow(new CreateUserFiatWallets($admin));
        }
        if ($admin->wallets->isEmpty()) {
            $admin->createTestWallet(\App\Models\Currency::ID_BTC);
        }

        $admin->removeRoles($admin->roles);

        $admin->assignRoles('admin');

        // Admin Access
        $admin->givePermissionTo('access.admin');
    }
}
