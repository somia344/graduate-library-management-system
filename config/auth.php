<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        'librarian' => [
            'driver' => 'session',
            'provider' => 'librarians',
        ],
        
        'student' => [
            'driver' => 'session',
            'provider' => 'students',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        
        'librarians' => [
            'driver' => 'eloquent',
            'model' => App\Models\Librarian::class,
        ],
        
        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'librarians' => [
            'provider' => 'librarians',
            'table' => 'librarian_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'students' => [
            'provider' => 'students',
            'table' => 'student_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];