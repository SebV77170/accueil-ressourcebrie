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
        $this->service->create($request->validated());
        return back();
    }

    public function update($id, UpdateTaskRequest $request)
    {
        $this->service->update($id, $request->validated());
        return back();
    }

    public function complete($id)
    {
        $this->service->toggleComplete($id);
        return back();
    }

    public function archive($id)
    {
        $this->service->archive($id);
        return back();
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return back();
    }
}
