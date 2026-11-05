<?php

return [
    'sms' => [
        'driver' => env('SMS_DRIVER', 'ilkom'), // ilkom, twilio, nexmo, etc
        'url' => env('SMS_API_URL', 'https://api.ilkom.com.tr/sms/send'),
        'api_key' => env('SMS_API_KEY', ''),
        'api_secret' => env('SMS_API_SECRET', ''),
        'sender' => env('SMS_SENDER', 'PERGEL'),
        'test_mode' => env('SMS_TEST_MODE', true), // Development'da SMS göndermez, log'a yazar
    ],
];
