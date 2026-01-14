<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('webdav');
        $files = collect($disk->listContents('/', false))
            ->filter(fn ($item) => ($item['type'] ?? '') === 'file')
            ->map(fn ($item) => [
                'path' => $item['path'] ?? '',
                'basename' => basename($item['path'] ?? ''),
                'size' => $item['size'] ?? 0,
                'last_modified' => $item['last_modified'] ?? null,
            ])
            ->values();

        return view('files.index', [
            'files' => $files,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:51200'],
        ]);

        $disk = Storage::disk('webdav');

        foreach ($validated['files'] as $file) {
            $disk->putFileAs('/', $file, $file->getClientOriginalName());
        }

        return redirect()->route('files.index')->with('success', 'Fichiers téléversés avec succès.');
    }

    public function download(string $path)
    {
        $normalizedPath = ltrim($path, '/');
        $disk = Storage::disk('webdav');

        if ($normalizedPath === '' || !$disk->exists($normalizedPath)) {
            abort(404);
        }

        $stream = $disk->readStream($normalizedPath);

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, basename($normalizedPath));
    }
}
