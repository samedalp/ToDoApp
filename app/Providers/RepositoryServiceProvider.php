<?php

namespace App\Providers;

use App\Repository\DeveloperRepositoryInterface;
use App\Repository\Eloquent\DeveloperRepository;
use App\Repository\Eloquent\ProviderRepository;
use App\Repository\Eloquent\TaskRepository;
use App\Repository\EloquentRepositoryInterface;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\ProviderRepositoryInterface;
use App\Repository\TaskRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(DeveloperRepositoryInterface::class, DeveloperRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(ProviderRepositoryInterface::class, ProviderRepository::class);
    }
}
