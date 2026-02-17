<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\EmailVerified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('scooters.index', absolute: false));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new EmailVerified($request->user()));
        }

        return redirect()->intended(route('scooters.index', absolute: false))->with('status', 'Email vérifié !');
    }
}
