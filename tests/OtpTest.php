<?php

namespace CreatvStudio\Otp\Tests;

use OTPHP\TOTP;
use CreatvStudio\Otp\HasOtp;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use CreatvStudio\Otp\OtpServiceProvider;
use CreatvStudio\Otp\Http\Middleware\CheckOtpSession;

class OtpTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        $this->loadLaravelMigrations();

        require_once __DIR__ . '/../database/migrations/otp_setup_table.php';

        (new \OtpSetupTable)->up();
    }

    protected function generateUser()
    {
        return TestUser::create([
            'name' => 'Alice',
            'email' => 'alice@mail.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [OtpServiceProvider::class];
    }

    /** @test */
    public function user_otp_returns_totp_instance()
    {
        $user = $this->generateUser();

        $this->assertInstanceOf(TOTP::class, $user->otp());
    }

    /** @test */
    public function user_can_create_otp_code()
    {
        $user = $this->generateUser();
        $otp = $user->getOtpCode();

        $this->assertIsInt((int) $otp);
    }

    /** @test */
    public function user_can_verify_code()
    {
        $user = $this->generateUser();
        $password = $user->getOtpCode();

        $this->assertNotNull($user->otp_uri);
        $this->assertTrue($user->verifyOtp($password));
    }

    /** @test */
    public function middleware_allows_valid_otp_session()
    {
        $user = $this->generateUser();

        $this->actingAs($user);

        $otpSession = $user->otpSessions()->create([
            'token' => 'valid-token',
        ]);

        $request = new Request;

        $request->cookies->add([
            'otp_session' => 'valid-token',
        ]);

        $middleware = new CheckOtpSession;

        $response = $middleware->handle($request, function () {
            return 'foo';
        });

        $this->assertEquals($response, 'foo');
    }

    /** @test */
    public function middleware_redirects_invalid_otp_session()
    {
        $user = $this->generateUser();

        $this->actingAs($user);

        $otpSession = $user->otpSessions()->create([
            'token' => 'valid-token',
        ]);

        $request = new Request;

        $request->cookies->add([
            'otp_session' => 'invalid-token',
        ]);

        $middleware = new CheckOtpSession;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertStringContainsStringIgnoringCase('/otp', $response->getTargetUrl());
    }
}

class TestUser extends User
{
    use HasOtp;

    protected $table = 'users';

    protected $guarded = [];
}
