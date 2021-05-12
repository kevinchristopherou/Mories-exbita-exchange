<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\FiatWalletCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\WalletCollection;
use App\Jobs\Wallets\CreateUserFiatWallets;
use App\Jobs\Wallets\CreateUserWallets;
use App\Mail\UserForgotPasswordEmail;
use App\Mail\UserPasswordChangedEmail;
use App\Mail\UserResetPasswordEmail;
use App\Mail\UserVerificationEmail;
use App\Models\LoginAttempt;
use App\Models\UserAllowedIp;
use App\Services\InternalApiClient;
use App\Helpers\SiteSettingsHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class UserController extends ApiBaseController
{
    public function register(Request $request)
    {
	$rules = [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|strong_password',
        ];
        
	$captchaSiteKey = SiteSettingsHelper::get('NOCAPTCHA_SITEKEY');
        $captchaSecretKey = SiteSettingsHelper::get('NOCAPTCHA_SECRET');

        $isCaptchaInstalled = $captchaSiteKey['value'] && $captchaSecretKey['value'];

	if($isCaptchaInstalled) {
	    $rules['g_recaptcha_response'] = 'required|captcha';	
	}

	$validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('register_validation_failed', 422, $validator->errors());
        }

        $user = User::create([
            'name' => $request->get('email'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'verify_token' => User::generateToken(),
            'locale' => App::getLocale(),
        ]);

        $mail = (new UserVerificationEmail($user))
            ->onQueue('emails')
            ;

        Mail::to($user)->send($mail);

        return $this->sendOK(null, 201);
    }

    public function resendVerification(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->sendError('register_validation_failed', 422, $validator->errors());
        }

        $user = User::where('email', $request->get('email'))->first();

        if(!$user) {
            return $this->sendError('account_not_found');
        }

        $last_sent = $user->mail_sent_at;
        if($last_sent) {
            $now = Date('Y-m-d H:i:s');
            $diff = strtotime($now) - strtotime($last_sent);

            if ($diff < 300) {
                return $this->sendError('account_resend_timeout');
            }
        }

        $mail = (new UserVerificationEmail($user))
            ->onQueue('emails');
        Mail::to($user)->send($mail);

        $user->mail_sent_at = Date('Y-m-d H:i:s');
        $user->save();

        return $this->sendOK(null, 201);
    }

    /**
     * This is just a proxy api endpoint to obtain access_token of the user without revealing our internal client secret in the front-end client.
     * @param Request $request
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $response = InternalApiClient::login($email, $password);
        $code = $response->getStatusCode();
        $r = json_decode($response->getContent());

        if ($code == '200') {
            return $response;
        } elseif ($code >= 400 && $code <= 499) {
            return $this->sendError($r->error, $code);
        } else {
            return $this->sendError('server_error', 500);
        }
    }

    public function loginCaptchaStatus(Request $request)
    {
        return $this->sendOK(['required' => LoginAttempt::isCaptchaRequired($request->ip())]);
    }

    public function personalToken(Request $request)
    {
        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'scopes' => 'array|in:'.implode(',', Passport::scopeIds()),
            ]);

            if ($validator->fails()) {
                return $this->sendError('', 422, $validator->errors());
            }
        }

        $response = InternalApiClient::personalToken($request);
        $code = $response->getStatusCode();

        if ($code == '200') {
            return $response;
        } else {
            return $this->sendError('server_error', 500);
        }
    }

    public function revokePersonalToken(Request $request)
    {
        $response = InternalApiClient::revokePersonalToken($request);
        $code = $response->getStatusCode();

        if ($code == '204') {
            return $response;
        } else {
            return $this->sendError('server_error', 500);
        }
    }

    public function scopes(Request $request)
    {
        $response = InternalApiClient::scopes($request);
        $code = $response->getStatusCode();

        $r = json_decode($response->getContent());

        if ($code == '200') {
            return $response;
        } elseif ($code >= 400 && $code <= 499) {
            return $this->sendError($r->error, $code);
        } else {
            return $this->sendError('server_error', 500);
        }
    }

    public function refreshToken(Request $request)
    {
        $response = InternalApiClient::refreshToken($request->get('refresh_token'));
        $code = $response->getStatusCode();
        $r = json_decode($response->getContent());

        if ($code == '200') {
            return $response;
        } elseif ($code >= 400 && $code <= 499) {
            return $this->sendError($r->error, $code);
        } else {
            return $this->sendError('server_error', 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $accessToken = $user->token();
        $accessToken->revoke();
        $accessToken->delete();
    }

    public function verify($id, $token)
    {
        $user = User::where('id', $id)->first();

        if ($user === null) {
            return $this->sendError('account_verify_user_not_found');
        }

        if ($user->status == 1) {
            return $this->sendError('account_verify_already_verified');
        }

        if ($user->verify_token != $token) {
            return $this->sendError('account_verify_invalid_token');
        }

        $user->status = 1;
        $user->verify_token = User::generateToken();
        $user->save();

        $job = (new CreateUserFiatWallets($user))
            ->onQueue('wallets')
            ;

        dispatch($job);

        $job = (new CreateUserWallets($user))
            ->onQueue('wallets')
            ;

        dispatch($job);

        return $this->sendOK();
    }

    public function deactivate($id, $token)
    {
        $user = User::where('id', $id)->first();

        if ($user === null) {
            return $this->sendError('account_deactivate.user_not_found');
        }

        if ($user->status != 1) {
            return $this->sendError('account_deactivate.already_deactivated');
        }

        if ($user->verify_token != $token) {
            return $this->sendError('account_deactivate.invalid_token');
        }

        $user->status = 2;
        $user->verify_token = User::generateToken();
        $user->save();
        $user->revokeAllTokens();

        return $this->sendOK();
    }

    public function verifyIp($id, $token)
    {
        $allowedIp = UserAllowedIp::where('id', $id)->first();

        if ($allowedIp === null || $allowedIp->verify_token !== $token) {
            return $this->sendError('login_ip_verify_failed');
        }

        $allowedIp->verified = 1;
        $allowedIp->save();

        return $this->sendOK($allowedIp);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'g_recaptcha_response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return $this->sendError('', 422, $validator->errors());
        }

        $user = User::where('email', $request->get('email'))->first();
        $token = Password::createToken($user);

        $mail = (new UserForgotPasswordEmail($user, $token))->onQueue('emails');
        Mail::to($user)->send($mail);

        return $this->sendOK();
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('id', $request->get('id'))
            ->where('email', $request->get('email'))
            ->first();

        if (! $user) {
            return $this->sendError('user_not_found', 404);
        }

        $exists = Password::tokenExists($user, $request->get('token'));

        if (! $exists) {
            return $this->sendError('invalid_reset_token', 400);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed|strong_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('', 422, $validator->errors());
        }

        $user->password = bcrypt($request->get('password'));
        $user->last_password_reset = Carbon::now();
        $user->save();

        $user->revokeAllTokens();

        $mail = (new UserResetPasswordEmail($user))->onQueue('emails');
        Mail::to($user)->send($mail);

        return $this->sendOK();
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|strong_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation_failed', 422, $validator->errors());
        }

        $user->password = bcrypt($request->get('password'));
        $user->save();

        $accessToken = $user->token();
        $user->revokeOtherTokens($accessToken);

        $mail = (new UserPasswordChangedEmail($user))->onQueue('emails');
        Mail::to($user)->send($mail);

        return $this->sendOK();
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        return $this->sendOK($user);
    }

    public function setLocale(Request $request)
    {
        $user = $request->user();
        $user->locale = $request->get('locale');
        $user->save();

        return $this->sendOK();
    }

    public function users()
    {
        // Get a collection of Users
        return new UserCollection(User::all());
    }

    public function getIdDocuments(Request $request)
    {
        $user = $request->user();

        return $this->sendOK($user->idDocuments);
    }

    public function postIdDocuments(Request $request)
    {
        $user = $request->user();

        return $this->sendOK($user->idDocuments);
    }

    public function wallets(Request $request)
    {
        $user = $request->user();

        return new WalletCollection($user->wallets);
    }

    public function fiatwallets(Request $request)
    {
        $user = $request->user();

        return new FiatWalletCollection($user->fiatWallets);
    }
}
