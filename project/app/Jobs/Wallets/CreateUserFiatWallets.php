<?php

namespace App\Jobs\Wallets;

use App\Models\FiatCurrency;
use App\Models\FiatWallet;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateUserFiatWallets implements ShouldQueue
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
            $fiats = FiatCurrency::all();
            foreach ($fiats as $fiat) {
                $this->createFiatWallet($fiat->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Unable to create fiat wallet for user '.$this->user->id);
            Log::error($e);
            DB::rollback();

            throw $e;
        }
    }

    private function createFiatWallet($currency_id)
    {
        $wallet = new FiatWallet();
        $wallet->user_id = $this->user->id;
        $wallet->fiat_currency_id = $currency_id;
        $wallet->save();

        return $wallet;
    }
}
