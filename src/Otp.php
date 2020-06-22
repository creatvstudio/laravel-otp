<?php

namespace CreatvStudio\Otp;

use Illuminate\Support\Facades\Route;

class Otp
{
    public function routes($options = [])
    {
        Route::get('otp/verify', 'OtpController@index')->name('otp.index');
        Route::post('otp/verify', 'OtpController@verify')->name('otp.verify');

        if ($options['resend'] ?? true) {
            Route::post('otp/resend', 'OtpController@resend')->name('otp.resend');
        }
    }
}
