<?php

namespace App\Http\Controllers\Otp;

use App\Http\Controllers\Controller;

class OtpController extends Controller
{
    public function index()
    {
        return view('otp.index');
    }

    public function verify()
    {
    }

    public function resend()
    {
    }
}
