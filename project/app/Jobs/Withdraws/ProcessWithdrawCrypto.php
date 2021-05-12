<?php

namespace App\Jobs\Withdraws;

use App\Events\CurrencyAccountBalanceUpdated;
use App\Mail\AdminWithdrawFailEmail;
use App\Models\Currency;
use App\Services\AdminMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWithdrawCrypto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $withdraw = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currency = Currency::where('id', $this->withdraw->currency_id)->first();

        //Check if currency account_balance is enough to make the payment
        if ($currency->account_balance_decimal < $this->withdraw->amount_decimal) {
            //Mark it as waiting, so it gets re-queued by scheduled SendTx job
            $this->withdraw->status = 'waiting';
            $this->withdraw->save();

            $mail = (new AdminWithdrawFailEmail($this->withdraw, 'insufficient_balance'))
                ->onQueue('emails')
                ;

            $mailer = new AdminMailer($mail, ['admin']);
            $mailer->send();

            return;
        }

        try {
            $this->withdraw->proceed();
            $this->withdraw->status = 'completed';
            $this->withdraw->save();
            event(new CurrencyAccountBalanceUpdated($this->withdraw->currency_id));

            return;
        } catch (WithdrawException $e) {
            Log::error($e);
            $this->sendErrorMails($e->getMessage()."\n\n".$e->getTraceAsString());
        } catch (\Exception $e) {
            Log::error($e);
            $this->sendErrorMails($e->getMessage()."\n\n".$e->getTraceAsString());
        }

        //If send fails somehow, mark it as waiting, so it gets re-queued by scheduled SendTx job
        $this->withdraw->status = 'waiting';
        $this->withdraw->save();
    }

    private function sendErrorMails($message)
    {
        $mail = (new AdminWithdrawFailEmail($this->withdraw, 'unexpected_error'))
            ->onQueue('emails')
            ;

        $mail->setMessage($message);

        $mailer = new AdminMailer($mail, ['admin']);
        $mailer->send();
    }
}
