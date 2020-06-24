<?php

namespace CreatvStudio\Otp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOtpOnce
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (200 !== $response->getStatusCode()) {
            return $response;
        }

        if ($request->session()->has('otp_once')) {
            $request->session()->forget(['otp_once', 'otp_once_input']);

            return $response;
        }

        Auth::user()->sendOtpCode();

        $request->session()->put('otp_once_input', $request->input());

        return redirect()->guest(route('otp.once.index'));
    }
}
