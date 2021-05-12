<?php

namespace Tests;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Mail\UserNewLoginEmail;
use App\Services\InternalApiClient;
use App\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

abstract class ApiTestCase extends BaseTestCase
{
    protected $forceResetDb = true;

    protected $apiVersion = '';

    protected $apiUri = '';

    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiVersion = config('app.api_version');
        $this->apiUri = 'api/'.$this->apiVersion.'/';

        //Mocking NoCaptcha verifyResponse
        NoCaptcha::shouldReceive('verifyResponse')
            ->andReturn(true);

        //In some tests we need to reset the application during the test without resetting DB. In such cases we omit "RefreshesDatabase" trait and need to trigger reset db manually set this var to true in the test class
        if ($this->forceResetDb) {
            $this->resetDB();
        }

        $this->clearLogs();
        $this->setupPassport();
        //This is necessary since we setup our oauth-clients for each test case
        //Static client in InternalApiClient also needs to be reset
        InternalApiClient::setup();
    }

    private function setupPassport()
    {
        Artisan::call('passport:install');
    }

    private function clearLogs()
    {
        $logpath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'../logs/laravel.log';
        exec('echo "" > '.$logpath);
    }

    public function createUser($data = [])
    {
        $user = factory(\App\User::class)->create($data);

        return $user;
    }

    public function getUserToken($user, $password = 'Secret1!')
    {
        Mail::fake();

        $data = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->apiPost('user/login', $data);
        $response->assertStatus(200);

        if (! $user->two_fa_enabled) {
            Mail::assertQueued(UserNewLoginEmail::class);
        }

        return $response->json()['access_token'];
    }

    public function getHeaders($token)
    {
        return [
            'Authorization' => 'Bearer '.$token,
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }

    public function getAdminHeaders()
    {
        $admin = User::whereHas('roles', function ($query) {
            return $query->where('slug', 'admin');
        })->first();

        return $this->getHeaders($this->getUserToken($admin, 'gite123!'));
    }

    /**
     * This method is used to reset the authenticated user during rest api tests.
     * Without refreshing the application, an unauthorized api call after an authorized request still uses the
     * authenticated user from the previous authorized call.
     */
    public function resetState()
    {
        $this->refreshApplication();
        $this->createApplication();
        Facade::clearResolvedInstances();
    }

    protected function resetDB()
    {
        $this->artisan('config:cache');
        sleep(1);
        $this->artisan('migrate:fresh', ['--seeder' => 'DatabaseTestingSeeder']);
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Api call wrappers with proper api versions, all making json calls.
     */
    protected function apiGet($uri, $headers = [])
    {
        return $this->getJson($this->apiUri.$uri, $headers);
    }

    protected function apiPost($uri, $data = [], $headers = [])
    {
        return $this->postJson($this->apiUri.$uri, $data, $headers);
    }

    protected function apiPut($uri, $data = [], $headers = [])
    {
        return $this->putJson($this->apiUri.$uri, $data, $headers);
    }

    protected function apiPatch($uri, $data = [], $headers = [])
    {
        return $this->patchJson($this->apiUri.$uri, $data, $headers);
    }

    protected function apiDelete($uri, $data = [], $headers = [])
    {
        return $this->deleteJson($this->apiUri.$uri, $data, $headers);
    }

    protected function dumpAsArray($var)
    {
        if (is_object($var) && $var instanceof \Illuminate\Database\Eloquent\Model) {
            dump($var->toArray());
        } else {
            dump($var);
        }
    }
}
