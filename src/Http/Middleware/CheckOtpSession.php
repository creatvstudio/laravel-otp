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
        if (! Auth::user()->checkOtpSession($request->cookie('otp_session'))) {
            if (! auth()->user()[auth()->user()->getOtpUriName()]) {
                auth()->user()->updateOtpUri();
            }

            if (! session()->has('otp_intended_url')) {
                session(['otp_intended_url' => $request->url()]);
                session()->save();
            }

            return redirect(route('otp.index'));
        }

        return $next($request);
    }
}
