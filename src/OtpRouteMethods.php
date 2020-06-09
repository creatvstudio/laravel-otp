<?php

namespace CreatvStudio\Otp;

class OtpRouteMethods
{
    public function otp()
    {
        return function ($options = []) {
            $this->get('otp', 'Otp\OtpController@index')->name('otp.index');
            $this->post('otp/verify', 'Otp\OtpController@verify')->name('otp.verify');
            $this->post('otp/resend', 'Otp\OtpController@resend')->name('otp.resend');
        };
    }
}
