<?php

return [
    /*
    |--------------------------------------------------------------------------
    | chat app name
    |--------------------------------------------------------------------------
    |
    */
    'name'=> 'my chat app',


    'work_count' => 4,

    'registerAddress' => '127.0.0.1:1238',

    'registerServer' => 'text://0.0.0.0:1238',

    'lanIp' => '127.0.0.1',

    'gateway_app' => 'my gateway app',

    'socket_ip' => '0.0.0.0',

    'start_port' => 4000,

    'socket_port' => '8282',
    /*
    |--------------------------------------------------------------------------
    | User Model & Table
    |--------------------------------------------------------------------------
    |
    */
    'user' => 'App\User',

    'user_table' => 'users',

    'user_guard' => 'web',
    /*
    |--------------------------------------------------------------------------
    | User Model & Table
    |--------------------------------------------------------------------------
    |
    */
    'admin' => 'App\Admin',

    'admin_table' => 'admins',

    'admin_guard' => 'admin',
];