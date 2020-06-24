<?php

namespace CreatvStudio\Otp\Tests;

use CreatvStudio\Otp\HasOtp;
use CreatvStudio\Otp\Http\Middleware\CheckOtpOnce;
use CreatvStudio\Otp\Http\Middleware\CheckOtpSession;
use CreatvStudio\Otp\OtpServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use OTPHP\TOTP;

class OtpTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $provider = new OtpServiceProvider($this->app);

        $provider->loadRoutes(__DIR__ . '/routes.php');
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

    /**
     * @test
     */
    public function user_can_create_otp_qrcode()
    {
        // Arrange
        $user = $this->generateUser();

        // Act
        $qrCode = $user->getOtpQrCode();

        // Assert
        $this->assertNotNull($qrCode);

        $this->assertTrue(false !== filter_var($qrCode, FILTER_VALIDATE_URL));
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

        $token = $user->rememberOtpSession();

        $request = new Request();

        $request->cookies->add([
            $user->getOtpSessionId() => $token,
        ]);

        $middleware = new CheckOtpSession();

        $response = $middleware->handle($request, function () {
            return 'foo';
        });

        $this->assertEquals($response, 'foo');
    }

    /** @test */
    public function middleware_redirects_invalid_otp_session()
    {
        Notification::fake();

        $user = $this->generateUser();

        $this->actingAs($user);

        $token = $user->rememberOtpSession();

        $request = new Request();

        $request->cookies->add([
            $user->getOtpSessionId() => $token . 'invalid-token',
        ]);

        $middleware = new CheckOtpSession();

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertStringContainsStringIgnoringCase('/otp', $response->getTargetUrl());

        Notification::assertSentTo([$user], \CreatvStudio\Otp\Notifications\SendOtpNotification::class);
    }

    /**
     * @test
     */
    public function middleware_redirects_invalid_otp_once_session()
    {
        // Arrange
        Notification::fake();

        $user = $this->generateUser();

        $this->actingAs($user);

        $request = new Request();

        $middleware = new CheckOtpOnce();

        // Act
        $response = $middleware->handle($request, function () {
            return new RedirectResponse('/otp/once');
        });

        // Assert
        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertStringContainsStringIgnoringCase('/otp/once', $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function middleware_allows_valid_otp_once_session()
    {
        // Arrange
        $user = $this->generateUser();

        $this->actingAs($user);

        $request = new Request();

        $request->session()->put('otp_once', Str::random(60));

        $middleware = new CheckOtpOnce();

        // Act
        $response = $middleware->handle($request, function () {
            $successResponse = new Response();

            return $successResponse;
        });

        // Assert
        $this->assertEquals($response, 'foo');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('otp.notification', \CreatvStudio\Otp\Notifications\SendOtpNotification::class);
    }

    protected function setUpDatabase()
    {
        $this->loadLaravelMigrations();

        require_once __DIR__ . '/../database/migrations/otp_setup_table.php';

        (new \OtpSetupTable())->up();
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
}

class TestUser extends User
{
    use Notifiable, HasOtp;

    protected $table = 'users';

    protected $guarded = [];
}
