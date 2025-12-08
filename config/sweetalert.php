<?php

return [
    'cdn' => env('SWEET_ALERT_CDN'),
    'alwaysLoadJS' => env('SWEET_ALERT_ALWAYS_LOAD_JS', false),
    'neverLoadJS' => env('SWEET_ALERT_NEVER_LOAD_JS', false),
    'timer' => env('SWEET_ALERT_TIMER', 5000),
    'width' => env('SWEET_ALERT_WIDTH', '32em'),
    'heightAuto' => env('SWEET_ALERT_HEIGHT_AUTO', true),
    'padding' => env('SWEET_ALERT_PADDING', '1.25em'),
    'animation' => [
        'enable' => env('SWEET_ALERT_ANIMATION_ENABLE', false),
    ],
    'animatecss' => env('SWEET_ALERT_ANIMATECSS', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'),
    'show_confirm_button' => env('SWEET_ALERT_CONFIRM_BUTTON', true),
    'show_close_button' => env('SWEET_ALERT_CLOSE_BUTTON', false),
    'toast_position' => env('SWEET_ALERT_TOAST_POSITION', 'top-end'),
    'middleware' => [
        'autoClose' => env('SWEET_ALERT_MIDDLEWARE_AUTO_CLOSE', false),
        'toast_position' => env('SWEET_ALERT_MIDDLEWARE_TOAST_POSITION', 'top-end'),
        'toast_close_button' => env('SWEET_ALERT_MIDDLEWARE_TOAST_CLOSE_BUTTON', true),
        'timer' => env('SWEET_ALERT_MIDDLEWARE_TIMER', 6000),
        'auto_display_error_messages' => env('SWEET_ALERT_AUTO_DISPLAY_ERROR_MESSAGES', false),
    ],
];