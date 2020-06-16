<?php

namespace CreatvStudio\Otp\Http\Middleware;

use Closure;
use CreatvStudio\Otp\ChecksOtpSessionIdentifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOtpSession
{
    use ChecksOtpSessionIdentifier;

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
        if (! Auth::user()->checkOtpSession($request->cookie($this->getOtpSessionId()))) {
            if (! Auth::user()[Auth::user()->getOtpUriName()]) {
                Auth::user()->updateOtpUri();
            }

            return redirect(route('otp.index'));
        }

        return $next($request);
    }
}
