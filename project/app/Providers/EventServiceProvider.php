<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\FiatCurrency;
use App\Models\FiatWallet;
use App\Models\Order;
use App\Models\Wallet;
use App\Observers\CurrencyObserver;
use App\Observers\FiatCurrencyObserver;
use App\Observers\FiatWalletObserver;
use App\Observers\OrderObserver;
use App\Observers\WalletObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        // Observe Order Model
        Order::observe(OrderObserver::class);
        Currency::observe(CurrencyObserver::class);
        FiatCurrency::observe(FiatCurrencyObserver::class);
        Wallet::observe(WalletObserver::class);
        FiatWallet::observe(FiatWalletObserver::class);
    }
}
