<?php
namespace App\Http\Controllers;

use App\Services\Tasks\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;

class CaTaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    public function index()
    {
        $tasks = $this->service->list();
        $users = User::orderBy('name')->get();

        return view('ca.tasks.index', compact('tasks', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        return $this->handleAction(function () use ($request) {
            $this->service->create($request->validated());
        });
    }

    public function update($id, UpdateTaskRequest $request)
    {
        return $this->handleAction(function () use ($id, $request) {
            $this->service->update($id, $request->validated());
        });
    }

    public function complete($id)
    {
        return $this->handleAction(function () use ($id) {
            $this->service->toggleComplete($id);
        });
    }

    public function archive($id)
    {
        return $this->handleAction(function () use ($id) {
            $this->service->archive($id);
        });
    }

    public function destroy($id)
    {
        return $this->handleAction(function () use ($id) {
            $this->service->delete($id);
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
