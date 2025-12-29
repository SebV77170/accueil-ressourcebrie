<?php

namespace App\Http\Controllers;

use App\Services\Sites\SiteService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;

class SiteController extends Controller
{
    public function __construct(
        private SiteService $service
    ) {}

    public function index()
    {
        return view('sites.index', [
            'sites' => $this->service->list(),
            'categories' => \App\Models\Category::orderBy('nom')->get(),       
        ]);
    }

    

    public function store(StoreSiteRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->back()->with('success', 'Site ajouté avec succès.');
    }

    public function update(UpdateSiteRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->back()->with('success', 'Site mis à jour avec succès.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->back();
    }
}
