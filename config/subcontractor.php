<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subcontractor Module Database Connection
    |--------------------------------------------------------------------------
    |
    | This configuration determines which database connection should be used
    | when interacting with subcontractor portal models (users, contracts,
    | service entries, material entries, etc.). By default it falls back to
    | the application's primary connection, but you can override it by
    | defining SUBCONTRACTOR_DB_CONNECTION in the environment.
    |
    */
    'connection' => env('SUBCONTRACTOR_DB_CONNECTION', env('DB_CONNECTION', 'sqlite')),
];
