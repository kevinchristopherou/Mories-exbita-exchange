<?php

namespace App\Console;

use App\Models\Currency;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Updates market stats such as 24h high/low/change/volume etc.
        $schedule->command('markets:updatestats')->everyMinute()->withoutOverlapping();

        //Updates market history table data for trading view chart data
        $schedule->command('markets:updatehistory')->everyMinute()->withoutOverlapping();

        //Checks for confirmed BTC Transactions
        $schedule->command('blockchain:confirmtxbtc')->everyTenMinutes()->withoutOverlapping();

        //Checks for confirmed ETH Transactions
        $schedule->command('blockchain:confirmtxeth')->everyMinute()->withoutOverlapping();

        //Checks for confirmed ERC Transactions
        $schedule->command('blockchain:confirmtxerc')->everyMinute()->withoutOverlapping();

        //Update BTC balance
        $schedule->command('blockchain:updatebalance_btc')->everyMinute()->withoutOverlapping();

        //Update ETH balance
        $schedule->command('blockchain:updatebalance_eth')->everyMinute()->withoutOverlapping();

        //Queue BTC withdraws
        //$schedule->command('blockchain:queuesendtx', [Currency::ID_BTC])->everyTenMinutes()->withoutOverlapping();

        //Execute frontned build preview for theme editor
        $schedule->command('build:frontend')->everyMinute()->withoutOverlapping();

        //Add missing wallet
        $schedule->command('user:addmissingwallets')->everyMinute()->withoutOverlapping();

        $schedule->command('license:state')->twiceDaily(1, 13)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
