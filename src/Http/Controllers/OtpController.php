<?php

namespace App\Http\Controllers\Otp;

use App\Http\Controllers\Controller;
use CreatvStudio\Otp\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    use RedirectsUsers;

    public function __construct()
    {
        $this->middleware([
            'auth',
            function ($request, $next) {
                if (Auth::user()->checkOtpSession($request->cookie(Auth::user()->getOtpSessionId()))) {
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
                            $fail('The OTP Code is invalid.');
                        }
                    },
                ],
            ],
            [],
            [
                'otp' => 'OTP Code',
            ]
        );

        Auth::user()->rememberOtpSession();

        return redirect()->intended($this->redirectPath());
    }

    public function resend()
    {
        Auth::user()->sendOtpCode();

        return redirect()->route('otp.index')->with('success', 'OTP Code has been sent.');
    }
}
