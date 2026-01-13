<?php
namespace App\Http\Controllers;

use App\Services\Tasks\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CaTaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $perPage = (int) $request->query('per_page', 10);
        $responsableId = $request->query('responsable');
        $responsableId = $responsableId !== null ? (int) $responsableId : null;
        $categoryId = $request->query('category');
        $categoryId = $categoryId !== null ? (int) $categoryId : null;
        $allowedPerPage = [5, 10, 20, 50];

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        if (! in_array($status, ['pending', 'completed', 'archived', 'all'], true)) {
            $status = 'all';
        }

        $tasks = $this->service->list(
            $status,
            $perPage,
            (int) $request->query('page', 1),
            $responsableId,
            $categoryId,
        );
        $tasks->appends($request->query());
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('nom')->get();

        return view('ca.tasks.index', compact('tasks', 'users', 'status', 'perPage', 'responsableId', 'categories', 'categoryId'));
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

    public function unarchive($id)
    {
        return $this->handleAction(function () use ($id) {
            $this->service->unarchive($id);
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
