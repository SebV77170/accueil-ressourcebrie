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
        return $this->handleAction(function () use ($taskId, $request) {
            $this->service->create($taskId, $request->validated());
        });
    }

    public function update($taskId, $subTaskId, UpdateSubTaskRequest $request)
    {
        return $this->handleAction(function () use ($taskId, $subTaskId, $request) {
            $this->service->update($taskId, $subTaskId, $request->validated());
        });
    }

    public function complete($taskId, $subTaskId)
    {
        return $this->handleAction(function () use ($taskId, $subTaskId) {
            $this->service->toggleComplete($taskId, $subTaskId);
        });
    }

    public function archive($taskId, $subTaskId)
    {
        return $this->handleAction(function () use ($taskId, $subTaskId) {
            $this->service->archive($taskId, $subTaskId);
        });
    }

    public function destroy($taskId, $subTaskId)
    {
        return $this->handleAction(function () use ($taskId, $subTaskId) {
            $this->service->delete($taskId, $subTaskId);
        });
    }

    private function handleAction(callable $action)
    {
        try {
            $action();
        } catch (\DomainException | \RuntimeException $exception) {
            return back()
                ->withErrors(['global' => $exception->getMessage()])
                ->withInput();
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'global' => "Une erreur inattendue est survenue. Merci de réessayer ou de contacter l'équipe.",
                ])
                ->withInput();
        }

        return back();
    }
}
