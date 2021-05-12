<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/// !!!! DON'T USE ANY CLOSURE ROUTES, WE'RE USING ROUTE CACHING ON FRONTEND !!!! ////
///
Route::group(['prefix' => 'v1', 'middle'], function () {
    Route::group(['middleware' => ['throttle:600,1', 'license.check']], function () {
        Route::post('/user/login', 'UserController@login');
        Route::get('/user/lcs', 'UserController@loginCaptchaStatus');
        Route::post('/user/register', 'UserController@register');
        Route::post('/user/resend/emailverification', 'UserController@resendVerification');
        Route::post('/user/refreshtoken', 'UserController@refreshToken');
        Route::post('/user/forgotpassword', 'UserController@forgotPassword');
        Route::post('/user/resetpassword', 'UserController@resetPassword');

        Route::get('/user/{id}/verify/{token}', 'UserController@verify')->where('id', '[0-9]+');
        Route::get('/user/{id}/verifyip/{token}', 'UserController@verifyIp')->where('id', '[0-9]+');
        Route::get('/user/{id}/deactivate/{token}', 'UserController@deactivate')->where('id', '[0-9]+');

        //2FA routes, with auth:api middleware but without 2fa middleware.
        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('/twofa/verify', 'TwoFAController@verify');
            Route::get('/twofa/request', 'TwoFAController@requestCode');
        });

        Route::group(['middleware' => ['auth:api', '2fa']], function () {
            Route::get('/user', 'UserController@me');
            Route::post('/user/logout', 'UserController@logout');

            Route::group(['middleware' => ['2fa.otp']], function () {
                Route::put('/user/changepassword', 'UserController@changepassword');
            });

            Route::get('/wallets', 'UserController@wallets');
            Route::get('/fiatwallets', 'UserController@fiatwallets');

            Route::put('/user/setlocale', 'UserController@setLocale');
            Route::delete('/user/personaltoken/{token}', 'UserController@revokePersonalToken');
            Route::get('/user/personaltoken', 'UserController@personalToken');
            Route::post('/user/personaltoken', 'UserController@personalToken');
            Route::get('/user/scopes', 'UserController@scopes');
            Route::any('/twofa/setup/{method}', 'TwoFAController@setup');
            Route::get('/twofa/setupcomplete/{otp}', 'TwoFAController@setupComplete');
            Route::post('/twofa/cancel', 'TwoFAController@cancel');

            Route::get('/finance', 'FinanceController@index');
            Route::get('/iddocuments', 'IdDocumentsController@get');
            Route::post('/iddocuments', 'IdDocumentsController@store');
            Route::get('/referrals', 'UserController@referrals');
            Route::get('/referralearnings', 'UserController@referralEarnings');
            Route::get('/refcode', 'UserController@refcode');

            Route::post('file/upload/{type?}', 'FileController@upload')->middleware(['throttle:100']); // allow 10 file uploads per minute
            Route::get('file/token/{id}', 'FileController@downloadToken');
            Route::get('file/download/{token}/{nonce}', 'FileController@download');

            Route::get('/finance', 'FinanceController@index');

            Route::group(['middleware' => ['user.verified']], function () {

                // Deposits API
                Route::get('/deposit/fiat', 'FiatDepositController@get');
                Route::get('/deposit/fiat/details', 'FiatDepositController@details');
                Route::post('/deposit/fiat', 'FiatDepositController@store');
                Route::post('/deposit/crypto/generate', 'DepositController@generate');
                Route::get('/deposit/crypto/history', 'DepositController@history');
                Route::get('/deposit/crypto/pending', 'DepositController@pending');

                Route::get('/withdraw/fiat', 'FiatWithdrawController@get');
                Route::get('/withdraw/fiat/remembered', 'FiatWithdrawController@getRemembered');
                Route::post('/withdraw/fiat/remembered', 'FiatWithdrawController@postRemembered');
                Route::delete('/withdraw/fiat/remembered/{id}', 'FiatWithdrawController@deleteRemembered');

                Route::get('/withdraw/crypto', 'WithdrawController@get');
                Route::get('/withdraw/crypto/remembered', 'WithdrawController@getRemembered');
                Route::post('/withdraw/crypto/remembered', 'WithdrawController@postRemembered');
                Route::delete('/withdraw/crypto/remembered/{id}', 'WithdrawController@deleteRemembered')->where('id', '[0-9]+');
                Route::delete('/withdraw/crypto/{id}', 'WithdrawController@delete')->where('id', '[0-9]+');

                Route::post('/withdraw/validate/fiat', 'FiatWithdrawController@isValid');
                Route::post('/withdraw/validate/crypto', 'WithdrawController@isValid');

                Route::group(['middleware' => ['2fa.otp']], function () {
                    // Withdraw API
                    Route::post('/withdraw/fiat', 'FiatWithdrawController@store');
                    Route::post('/withdraw/crypto', 'WithdrawController@store');
                });
            });
        });

        Route::group(['middleware' => ['auth:api', 'auth.admin']], function () {

            // Users Api
            Route::get('/users', 'Admin\UserController@index');
            Route::get('/user/block/{id}', 'Admin\UserController@block');

            // Verifications Api
            Route::get('/verifications', 'Admin\VerificationController@index');
            Route::get('/verification/verify/{id}', 'Admin\VerificationController@verify');
            Route::get('/verification/reject/{id}', 'Admin\VerificationController@reject');

            // Available Coinpayments currencies
            Route::get('/settings/coinpayments-currencies', 'Admin\SettingsController@coinpaymentsCurrencies');

            //Settings Api
            Route::get('/settings/commission', 'Admin\SettingsController@commission');
            Route::patch('/settings/commission', 'Admin\SettingsController@storeCommission');

            Route::get('/settings/currencies', 'Admin\SettingsController@currencies');
            Route::patch('/settings/currencies', 'Admin\SettingsController@storeCurrencies');
            Route::post('/settings/currencies', 'Admin\SettingsController@deleteCurrencies');

            Route::get('/settings/fiatcurrencies', 'Admin\SettingsController@fiatCurrencies');
            Route::patch('/settings/fiatcurrencies', 'Admin\SettingsController@storeFiatCurrencies');
            Route::post('/settings/fiatcurrencies', 'Admin\SettingsController@deleteFiatCurrencies');

            Route::get('/site-settings', 'Admin\SiteSettingsController@index');
            Route::patch('/site-settings', 'Admin\SiteSettingsController@store');
            Route::get('/bitcoin-node-status', 'Admin\SiteSettingsController@getBitcoinNodeStatus');
            Route::get('/ethereum-node-status', 'Admin\SiteSettingsController@getEthereumNodeStatus');
            Route::get('/coinpayments-status', 'Admin\SiteSettingsController@getCoinpaymentsStatus');
            Route::get('/coinpayments-sync', 'Admin\SiteSettingsController@getCoinpaymentsSync');
            Route::get('/health-checker', 'Admin\SiteSettingsController@healthCheck');

            // Deposits API
            Route::get('/admin/deposit/fiat', 'Admin\FiatDepositController@index');
            Route::patch('/admin/deposit/fiat/{status}', 'Admin\FiatDepositController@moderate');

            // Deposit Crypto API
            Route::get('/admin/deposit/crypto', 'Admin\DepositController@index');

            // Withdraw Fiat API
            Route::get('/admin/withdraw/fiat', 'Admin\FiatWithdrawController@get');
            Route::post('/admin/withdraw/fiat', 'Admin\FiatWithdrawController@store');
            Route::patch('/admin/withdraw/fiat/{status}', 'Admin\FiatWithdrawController@moderate');

            // Withdraw Crypto API
            Route::get('/admin/withdraw/crypto', 'Admin\WithdrawController@get');
            Route::post('/admin/withdraw/crypto', 'Admin\WithdrawController@store');
            Route::patch('/admin/withdraw/crypto/{status}', 'Admin\WithdrawController@moderate');

            //Theme Editor
            Route::get('/admin/theme-editor', 'Admin\ThemeEditorController@index');
            Route::get('/admin/theme-editor/{id}', 'Admin\ThemeEditorController@get')->where('id', '[0-9]+');
            Route::patch('/admin/theme-editor/{id}', 'Admin\ThemeEditorController@update')->where('id', '[0-9]+');
            Route::get('/admin/theme-editor/trigger-build/{type}', 'Admin\ThemeEditorController@build');

            // Markets Admin  API
            Route::get('/admin/markets', 'Admin\MarketController@index');
            Route::post('/admin/markets', 'Admin\MarketController@store');
            Route::patch('/admin/markets', 'Admin\MarketController@update');
            Route::delete('/admin/markets/{id}', 'Admin\MarketController@destroy');

            // Locales Admin  API
            Route::get('/admin/locales', 'Admin\LocalesController@index');
            Route::get('/admin/locales/{locale}', 'Admin\LocalesController@get');
            Route::post('/admin/locales', 'Admin\LocalesController@store');
            Route::patch('/admin/locales', 'Admin\LocalesController@update');
            Route::delete('/admin/locales/{id}', 'Admin\LocalesController@destroy');

            // Referral System
            Route::get('/admin/referralearnings', 'Admin\UserController@referralEarnings');
        });

        // Market Rest API
        Route::get('/markets', 'MarketController@index');
        Route::get('/market/{market}', 'MarketController@show');
        Route::get('/market/{market}/orderbook/{type}', 'MarketController@orderBook');
        Route::get('/market/{market}/history', 'MarketController@history');

        // Currency Rest API
        Route::get('/currencies', 'CurrencyController@index');
        Route::get('/currencies/fiat', 'FiatCurrencyController@index');
        Route::get('/currencies/deposit/fiat', 'FiatCurrencyController@deposit');

        // Order Rest API
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('/orders', 'OrderController@index');
            Route::get('/order/{uuid}', 'OrderController@show');
            Route::group(['middleware' => ['scope:manage_orders']], function () {
                Route::post('/order/buy', 'OrderController@store')->name('order.buy');
                Route::post('/order/buylimit', 'OrderController@store')->name('order.buylimit');
                Route::post('/order/sell', 'OrderController@store')->name('order.sell');
                Route::post('/order/selllimit', 'OrderController@store')->name('order.selllimit');
                Route::post('/order/cancel', 'OrderController@cancel');
            });
        });
    });

    //Blockchain Rest API
    Route::get('/blockchain/txnotify/{symbol}/{txid}', 'BlockchainController@getTxNotify');
    Route::get('/blockchain/txupdate/{type}/{id}/{txid}', 'BlockchainController@updateTxNotify');
    Route::get('/blockchain/txbalancenotify/{symbol}/{txid}/{txid_main}', 'BlockchainController@transferBalanceNotify');

    // Coinpayments IPN
    Route::post('/blockchain/coinpayments/ipn', 'BlockchainController@getCoinpaymentsIpn');

    //TradingView API
    Route::group(['middleware' => ['throttle:1000,1']], function () {
        Route::get('/tradingviewudf/config', 'TradingViewController@config');
        Route::get('/tradingviewudf/symbols', 'TradingViewController@symbols');
        Route::get('/tradingviewudf/history', 'TradingViewController@history');
        Route::get('/tradingviewudf/time', 'TradingViewController@time');
    });
});
