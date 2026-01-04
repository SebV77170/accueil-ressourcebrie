<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubTaskRequest;
use App\Http\Requests\UpdateSubTaskRequest;
use App\Services\Tasks\SubTaskService;

class SubTaskController extends Controller
{
    public function __construct(private SubTaskService $service) {}

    public function store($taskId, StoreSubTaskRequest $request)
    {
        $this->service->create($taskId, $request->validated());
        return back();
    }

    public function update($taskId, $subTaskId, UpdateSubTaskRequest $request)
    {
        $this->service->update($taskId, $subTaskId, $request->validated());
        return back();
    }

    public function complete($taskId, $subTaskId)
    {
        $this->service->toggleComplete($taskId, $subTaskId);
        return back();
    }

    public function archive($taskId, $subTaskId)
    {
        $this->service->archive($taskId, $subTaskId);
        return back();
    }

    public function destroy($taskId, $subTaskId)
    {
        $this->service->delete($taskId, $subTaskId);
        return back();
    }
}
