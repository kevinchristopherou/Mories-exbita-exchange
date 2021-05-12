<?php

namespace App\Exceptions;

use Exception;

class WithdrawException extends Exception
{
    public $walletError = null;

    public function __construct($message, $walletError)
    {
        parent::__construct($message, 0, null);
        $this->walletError = $walletError;
    }

    public function getWalletError()
    {
        return $this->walletError;
    }
}
