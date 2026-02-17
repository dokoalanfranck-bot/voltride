<?php

return [
    /**
     * Stripe API Key (Secret)
     * Found in your Stripe dashboard: https://dashboard.stripe.com/apikeys
     */
    'secret' => env('STRIPE_SECRET_KEY'),

    /**
     * Stripe Publishable Key
     * Used for frontend forms
     */
    'public' => env('STRIPE_PUBLIC_KEY'),

    /**
     * Stripe Webhook Secret
     * For verifying webhook signatures
     */
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
];
