<?php

namespace App\Providers;

use App\Services\ConsultaCepService;
use App\Services\ConsultaCepServiceInterface;
use Illuminate\Support\ServiceProvider;

class ConsultaCepServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ConsultaCepServiceInterface::class,
            ConsultaCepService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
