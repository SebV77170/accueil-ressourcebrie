<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Tasks\Repositories\TaskRepository;
use App\Domain\Tasks\Repositories\EloquentTaskRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
