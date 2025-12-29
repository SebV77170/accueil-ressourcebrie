<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
{
    $data = $request->validate([
        'nom' => ['required', 'string', 'max:191', 'unique:categories,nom'],
    ]);

    return Category::create($data);
}
}
