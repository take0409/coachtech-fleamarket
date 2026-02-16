<?php

use Laravel\Fortify\Features;

return [
    'guard' => 'web',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'lowercase_usernames' => true,
    'home' => '/',
    'prefix' => '',
    'domain' => null,
    'middleware' => ['web'],
    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],
    'views' => true,
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(), // ← これを追加：メール認証機能を有効化
        Features::updateProfileInformation(),
        Features::updatePasswords(),
    ],
];