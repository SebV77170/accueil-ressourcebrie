<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::orderBy('categorie')
            ->orderBy('nom')
            ->get();

        $categories = Site::select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('sites.index', compact('sites', 'categories'));
    }
}
