<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Tasks\Repositories\TaskRepository;
use App\Domain\Tasks\Repositories\EloquentTaskRepository;
use App\Domain\Tasks\Repositories\TaskCommentRepository;
use App\Domain\Tasks\Repositories\EloquentTaskCommentRepository;
use App\Domain\Tasks\Repositories\SubTaskRepository;
use App\Domain\Tasks\Repositories\EloquentSubTaskRepository;
use App\Domain\Sites\Repositories\SiteRepository;
use App\Domain\Sites\Repositories\EloquentSiteRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
        $this->app->bind(TaskCommentRepository::class, EloquentTaskCommentRepository::class);
        $this->app->bind(SubTaskRepository::class, EloquentSubTaskRepository::class);
        $this->app->bind(
            SiteRepository::class,
            EloquentSiteRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
