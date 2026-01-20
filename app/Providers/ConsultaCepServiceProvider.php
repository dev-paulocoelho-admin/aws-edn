<?php

namespace App\Providers;

use App\Repositories\ConsultaCepRepository;
use App\Repositories\ConsultaCepRepositoryInterface;
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

        $this->app->bind(
            ConsultaCepRepositoryInterface::class,
            ConsultaCepRepository::class
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
