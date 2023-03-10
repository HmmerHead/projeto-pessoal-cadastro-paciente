<?php

namespace App\Providers;

use App\Repository\CNSRepository;
use Core\UseCase\Repository\CNSRepositoryInterface;
use App\Repository\FotoRepository;
use Core\UseCase\Repository\FotoRepositoryInterface;
use App\Repository\PacienteRepository;
use Core\UseCase\Repository\PacienteRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            PacienteRepositoryInterface::class,
            PacienteRepository::class
        );

        $this->app->singleton(
            CNSRepositoryInterface::class,
            CNSRepository::class
        );

        $this->app->singleton(
            FotoRepositoryInterface::class,
            FotoRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
