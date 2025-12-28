<?php

namespace App\Http\Controllers;

use App\Services\Sites\SiteService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSiteRequest;

class SiteController extends Controller
{
    public function __construct(
        private SiteService $service
    ) {}

    public function index()
    {
        return view('sites.index', [
            'sites' => $this->service->list(),
            'categories' => $this->service->categories(),
        ]);
    }

    

    public function store(StoreSiteRequest $request)
    {
        $this->service->create($request->validated());
    }

    public function update(Request $request, int $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->back();
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->back();
    }
}
