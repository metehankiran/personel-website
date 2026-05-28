<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Author
    |--------------------------------------------------------------------------
    |
    | Static, deployment-time identity for the site owner. Used in places that
    | cannot or should not rely on the database (error pages, maintenance
    | mode, transactional contexts where DB access may be unavailable).
    |
    | The database-backed GeneralSettings remain the source of truth for the
    | normal public site (homepage, footer, etc.) and can be edited via the
    | Filament admin panel.
    |
    */

    'author' => [
        'name' => env('AUTHOR_NAME'),
        'email' => env('AUTHOR_EMAIL'),
    ],

];
