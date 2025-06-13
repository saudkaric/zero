<?php

declare(strict_types=1);

namespace Zero\Middlewares;

class GuestMiddleware
{
    public static function handle()
    {
        if (false) {
            die('Error in GuestMiddleware');
        }
    }
}
