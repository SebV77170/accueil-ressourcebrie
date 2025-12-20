<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /**
     * Affiche la liste des sites.
     */
    public function index()
    {
        // Récupération de tous les sites, triés par catégorie puis par nom
        $sites = DB::table('sites')
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get();

        $categories = DB::table('sites')
            ->select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

            // Retourne la vue avec les sites
        return view('sites.index', compact('sites', 'categories'));

    }
}
