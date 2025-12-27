<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Tasks\Repositories\TaskRepository;
use App\Domain\Tasks\Repositories\EloquentTaskRepository;
use App\Domain\Tasks\Repositories\TaskCommentRepository;
use App\Domain\Tasks\Repositories\EloquentTaskCommentRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
        $this->app->bind(TaskCommentRepository::class, EloquentTaskCommentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
