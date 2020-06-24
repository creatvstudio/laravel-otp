<?php

namespace CreatvStudio\Otp;

use CreatvStudio\Otp\RedirectsUsers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Otp
{
    use RedirectsUsers;

    public function routes($options = [])
    {
        Route::prefix('otp')->name('otp.')->group(function () {
            Route::prefix('verify')->group(function () {
                Route::get('', 'OtpController@index')->name('index');
                Route::post('', 'OtpController@verify')->name('verify');
            });

            if ($options['resend'] ?? true) {
                Route::post('resend', 'OtpController@resend')->name('resend');
            }

            Route::prefix('once')->name('once.')->group(function () {
                Route::get('', 'OtpOnceController@index')->name('index');
                Route::post('', 'OtpOnceController@verify')->name('verify');

                if ($options['resendOnce'] ?? true) {
                    Route::post('resend', 'OtpOnceController@resend')->name('resend');
                }
            });
        });
    }

    public function rememberOtpOnce()
    {
        Session::put('otp_once', Str::random(60));
    }

    public function redirectOtpOnce()
    {
        $this->rememberOtpOnce();

        return redirect()->intended($this->redirectPath())->with('_old_input', Session::get('otp_once_input'));
    }
}
