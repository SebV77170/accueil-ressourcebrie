<?php

namespace App\Http\Controllers;

use App\Services\Tasks\TaskCommentService;
use App\Http\Requests\StoreTaskCommentRequest;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function __construct(private TaskCommentService $service) {}

    public function store($taskId, StoreTaskCommentRequest $request)
    {
        $this->service->add(
            taskId: $taskId,
            content: $request->validated()['content'],
            userId: Auth::id()
        );

        return back();
    }

    public function storeForSubTask($taskId, $subTaskId, StoreTaskCommentRequest $request)
    {
        $this->service->addForSubTask(
            taskId: $taskId,
            subTaskId: $subTaskId,
            content: $request->validated()['content'],
            userId: Auth::id()
        );

        return back();
    }
}
