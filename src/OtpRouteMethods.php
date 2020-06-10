<?php

namespace CreatvStudio\Otp;

class OtpRouteMethods
{
    public function otp()
    {
        return function ($options = []) {
            $this->get('otp/verify', 'Otp\OtpController@index')->name('otp.index');
            $this->post('otp/verify', 'Otp\OtpController@verify')->name('otp.verify');

            if ($options['resend'] ?? true) {
                $this->post('otp/resend', 'Otp\OtpController@resend')->name('otp.resend');
            }
        };
    }
}
