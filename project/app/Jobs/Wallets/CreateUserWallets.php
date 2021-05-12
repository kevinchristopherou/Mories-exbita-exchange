<?php

namespace App\Jobs\Wallets;

use App\Models\Currency;
use App\Models\Wallet;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateUserWallets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            $currencies = Currency::all();
            foreach ($currencies as $currency) {
                $this->createWallet($currency->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    private function createWallet($currency_id)
    {
        $wallet = new Wallet();
        $wallet->user_id = $this->user->id;
        $wallet->currency_id = $currency_id;
        $wallet->save();

        return $wallet;
    }
}
