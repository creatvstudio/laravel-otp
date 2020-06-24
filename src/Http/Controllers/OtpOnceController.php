<?php

namespace CreatvStudio\Otp\Http\Controllers;

use CreatvStudio\Otp\Facades\Otp;
use CreatvStudio\Otp\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpOnceController extends Controller
{
    use RedirectsUsers;

    public function __construct()
    {
        $this->middleware([
            'auth',
            function ($request, $next) {
                if ($request->session()->has('otp_once')) {
                    return back();
                }

                return $next($request);
            },
        ]);
    }

    public function index()
    {
        return view('vendor.otp.once');
    }

    public function verify(Request $request)
    {
        $request->validate($this->rules(), [], $this->messages());

        return Otp::redirectOtpOnce();
    }

    public function resend()
    {
        Auth::user()->sendOtpCode();

        return redirect()->route('otp.once.index')->with('success', 'OTP Code has been sent.');
    }

    public function rules()
    {
        return [
            'otp' => [
                'required',

                function ($attribute, $value, $fail) {
                    if (! Auth::user()->verifyOtp($value)) {
                        $fail('The OTP Code is invalid.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'otp' => 'OTP Code',
        ];
    }
}
