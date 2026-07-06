<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    /**
     * Domains commonly used to generate throwaway/fake addresses, used here
     * to reject obviously fake guest emails at reservation time.
     */
    private const BLOCKED_DOMAINS = [
        'mailinator.com', 'mailinator.net', 'mailinator.org',
        'guerrillamail.com', 'guerrillamail.net', 'guerrillamail.org', 'guerrillamail.biz',
        '10minutemail.com', '10minutemail.net', '20minutemail.com',
        'tempmail.com', 'temp-mail.org', 'temp-mail.io', 'tempmailo.com', 'tempmail.dev',
        'yopmail.com', 'yopmail.net', 'yopmail.fr',
        'throwawaymail.com', 'throwaway.email',
        'sharklasers.com', 'grr.la', 'guerrillamailblock.com',
        'trashmail.com', 'trashmail.me', 'trash-mail.com',
        'fakeinbox.com', 'fakemailgenerator.com',
        'dispostable.com', 'disposablemail.com',
        'getnada.com', 'maildrop.cc', 'mailnesia.com',
        'moakt.com', 'mohmal.com', 'emailondeck.com',
        'crazymailing.com', 'mintemail.com', 'mytemp.email',
        'spamgourmet.com', 'spambog.com', 'mailcatch.com',
        'discard.email', 'discardmail.com', 'anonbox.net',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !str_contains($value, '@')) {
            return;
        }

        $domain = strtolower(trim(substr($value, strrpos($value, '@') + 1)));

        if (in_array($domain, self::BLOCKED_DOMAINS, true)) {
            $fail('Les adresses email temporaires/jetables ne sont pas acceptées. Merci d\'utiliser une adresse email valide.');
        }
    }
}
