<?php

namespace App\Http\Controllers\Otp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware([
            'auth',
            function ($request, $next) {
                if (auth()->user()->checkOtpSession($request->cookie('otp_session'))) {
                    return back();
                }

                return $next($request);
            },
        ]);
    }

    public function index()
    {
        return view('otp.index');
    }

    public function verify()
    {
        request()->validate(
            [
                'otp' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (! auth()->user()->verifyOtp($value)) {
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

        $token = Str::random();

        auth()->user()->otpSessions()->create([
            'token' => $token,
        ]);

        Cookie::queue(Cookie::forever('otp_session', $token));

        return redirect(session()->pull('otp_intended_url'));
    }

    public function resend()
    {
        return 'Hi ' . auth()->user()->name . '. Your new OTP Code is : ' . auth()->user()->getOtpCode();
    }
}
