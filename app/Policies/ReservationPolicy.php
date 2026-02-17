<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;

class ReservationPolicy
{
    /**
     * Determine if the user can view the reservation
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can update the reservation
     */
    public function update(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the reservation
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || $user->isAdmin();
    }
}
