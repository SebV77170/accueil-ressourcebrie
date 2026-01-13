<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigurationController extends Controller
{
    public function edit(Request $request): View
    {
        return view('configuration.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sub_task_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'task_background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'colors-updated');
    }
}
