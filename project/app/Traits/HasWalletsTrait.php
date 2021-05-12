<?php

namespace App\Traits;

use App\Models\FiatWallet;
use App\Models\Wallet;
use App\Models\WalletAddress;
use App\Services\WalletManager;
use Illuminate\Support\Str;

trait HasWalletsTrait
{
    public function getFiatWallet($fiatCurrencyId)
    {
        $walletPropName = 'fiatWallet'.$fiatCurrencyId;
        if (isset($this->$walletPropName)) {
            return $this->$walletPropName;
        }

        $this->$walletPropName = FiatWallet::where('user_id', $this->id)->where('fiat_currency_id', $fiatCurrencyId)->first();

        return $this->$walletPropName;
    }

    public function getWallet($currencyId)
    {
        $walletPropName = 'wallet'.$currencyId;
        if (isset($this->$walletPropName)) {
            return $this->$walletPropName;
        }

        $this->$walletPropName = Wallet::where('user_id', $this->id)->where('currency_id', $currencyId)->first();

        return $this->$walletPropName;
    }

    public function createTestWallet($currency_id)
    {
        $wallet = new Wallet();
        $wallet->user_id = $this->id;
        $wallet->currency_id = $currency_id;
        $wallet->save();

        $this->createTestWalletAddress($wallet);
    }

    public function createTestWalletAddress(Wallet $wallet)
    {
        $wallet_address = new WalletAddress();
        $wallet_address->wallet_id = $wallet->id;
        $wallet_address->address = Str::random(32);
        $wallet_address->save();
    }

    public function createWalletAddress(Wallet $wallet)
    {
        $address = WalletManager::generateAddress($wallet->currency);

        if ($address) {

            $dest_tag = null;

            if(is_array($address)) {
                $dest_tag = $address['dest_tag'];
                $address = $address['address'];
            }

            $wallet_address = new WalletAddress();
            $wallet_address->wallet_id = $wallet->id;
            $wallet_address->address = $address;
            $wallet_address->dest_tag = $dest_tag;
            $wallet_address->save();

            return ['address' => $address, 'dest_tag' => $dest_tag];
        }

        return false;
    }
}
