<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    final function register(): void
    {
        //
    }

    final function boot(): void
    {
//        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
//            URL::forceScheme('https');
//        }
    }
}
