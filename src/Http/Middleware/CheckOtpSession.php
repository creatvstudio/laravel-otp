<?php

namespace CreatvStudio\Otp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOtpSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->checkOtpSession($request->cookie(Auth::user()->getOtpSessionId()))) {
            Auth::user()->sendOtpCode();

            return redirect()->guest(route('otp.index'));
        }

        return $next($request);
    }
}
