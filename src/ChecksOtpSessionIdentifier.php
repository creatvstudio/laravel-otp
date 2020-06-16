<?php

namespace CreatvStudio\Otp;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

trait ChecksOtpSessionIdentifier
{
    public function getOtpSessionId()
    {
        return md5('otp_session_' . Auth::user()->id);
    }

    protected function rememberOtpSession()
    {
        $token = Str::random(60);

        Auth::user()->otpSessions()->create([
            'token' => $token,
        ]);

        Cookie::queue(Cookie::forever($this->getOtpSessionId(), $token));
    }
}
