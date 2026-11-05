<?php

return [
    // Payment reminder configuration
    'reminder_days' => env('PAYMENT_REMINDER_DAYS', 3),

    // Notification settings
    'notifications' => [
        'email_enabled' => env('PAYMENT_EMAIL_ENABLED', true),
        'sms_enabled' => env('PAYMENT_SMS_ENABLED', true),
    ],

    // SMS settings (Vonage)
    'sms' => [
        'from' => env('VONAGE_SMS_FROM', 'ŞantiyeConnect'),
    ],

    // Penalty settings
    'penalty' => [
        'daily_rate' => env('PAYMENT_PENALTY_DAILY_RATE', 0.05), // 5% per day
        'max_penalty' => env('PAYMENT_PENALTY_MAX', 0.50), // Max 50% of original amount
    ],
];
