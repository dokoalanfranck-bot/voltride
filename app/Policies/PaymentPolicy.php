<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy
{
    /**
     * Determine if the user can view the payment
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->id === $payment->reservation->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can update the payment
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->isAdmin();
    }
}
