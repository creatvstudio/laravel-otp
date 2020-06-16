<?php

namespace App\Http\Controllers\Otp;

use App\Http\Controllers\Controller;
use CreatvStudio\Otp\ChecksOtpSessionIdentifier;
use CreatvStudio\Otp\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    use RedirectsUsers, ChecksOtpSessionIdentifier;

    public function __construct()
    {
        $this->middleware([
            'auth',
            function ($request, $next) {
                if (Auth::user()->checkOtpSession($request->cookie($this->getOtpSessionId()))) {
                    return back();
                }

                return $next($request);
            },
        ]);
    }

    public function index()
    {
        return view('vendor.otp.index');
    }

    public function verify(Request $request)
    {
        $request->validate(
            [
                'otp' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (! Auth::user()->verifyOtp($value)) {
                            $fail($attribute . ' is invalid.');
                        }
                    },
                ],
            ],
            [],
            [
                'otp' => 'OTP Code',
            ]
        );

        $this->rememberOtpSession();

        return redirect()->intended($this->redirectPath());
    }

    public function resend()
    {
        return 'Hi ' . Auth::user()->name . '. Your new OTP Code is : ' . Auth::user()->getOtpCode();
    }
}
