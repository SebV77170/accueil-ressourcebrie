<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(private FileService $fileService)
    {
    }

    public function index()
    {
        return response()->json([
            'files' => $this->fileService->listFiles(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:51200'],
        ]);

        $this->fileService->upload($validated['files']);

        return response()->json([
            'message' => 'Fichiers téléversés avec succès.',
        ]);
    }

    public function download(string $path)
    {
        $stream = $this->fileService->download($path);

        if (!$stream) {
            abort(404);
        }

        $filename = basename($path);

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, $filename);
    }
}
