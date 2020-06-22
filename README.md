# Laravel OTP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creatvstudio/laravel-otp.svg?style=flat-square)](https://packagist.org/packages/creatvstudio/laravel-otp)
[![Total Downloads](https://img.shields.io/packagist/dt/creatvstudio/laravel-otp.svg?style=flat-square)](https://packagist.org/packages/creatvstudio/laravel-otp)

<!-- [![Build Status](https://img.shields.io/travis/creatvstudio/laravel-otp/master.svg?style=flat-square)](https://travis-ci.org/creatvstudio/laravel-otp)
[![Quality Score](https://img.shields.io/scrutinizer/g/creatvstudio/laravel-otp.svg?style=flat-square)](https://scrutinizer-ci.com/g/creatvstudio/laravel-otp)
[![Total Downloads](https://img.shields.io/packagist/dt/creatvstudio/laravel-otp.svg?style=flat-square)](https://packagist.org/packages/creatvstudio/laravel-otp) -->

**This package is still in alpha stage.** A laravel package to implement timebased otp.

## Installation

You can install the package via composer:

``` bash
composer require creatvstudio/laravel-otp
```

Publish package

``` bash
php artisan vendor:publish --provider="CreatvStudio\Otp\OtpServiceProvider" 
```

Run the migrations

``` bash
php artisan migrate
```

Add to your `config/app.php`

```php
aliases => [
    ... 	
    'Otp' => \CreatvStudio\Otp\Facades\Otp::class,
    ...
],
```

Set your mail settings from `.env` file

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

Publish config file using the following command:

``` bash
php artisan vendor:publish --tag="otp-config"
```

## Usage

Add trait to your User class

```php
use CreatvStudio\Otp\HasOtp;

class User extends Authenticable {
    use Notifiable;
    use HasOtp;
}

// Generate an OTP
$otp = $user->getOtpCode();

// Verify an OTP
$user->verifyOtp($otp);
```

## Protecting routes

Add the following codes to `$routeMiddleware` of `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    ...
    'otp' => \CreatvStudio\Otp\Http\Middleware\CheckOtpSession::class,	
]
```

Use to your `routes/web.php`

```php
Otp::routes();

Route::middleware(['auth'])->group(function(){
    Route::get('otp-protected')->middleware('otp');	
});
```

> <br>Note: Default Laravel Authentication is required to make the otp routes work properly.<br><br>

## Customizing sending of OTP Code to Mail

To customize the contents of email when sending OTP Code, just modify `./app/Notifications/SendOtpNotification.php`

> <br>Note: Please refer to Official Laravel Documentation for [custom notifications](https://laravel.com/docs/7.x/notifications#custom-channels).<br><br>

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email jeff@creatvstudio.ph instead of using the issue tracker.

## Credits

- [Jeffrey Naval](https://github.com/creatvstudio)
- [Samuel Magana](https://github.com/maganasamuel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).