<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $documentsPath = public_path('documents');

        $documents = collect();

        if (File::exists($documentsPath)) {
            $documents = collect(File::files($documentsPath))
                ->filter(fn ($file) => $file->isFile())
                ->map(fn ($file) => [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'updated_at' => Carbon::createFromTimestamp($file->getMTime()),
                ])
                ->sortBy('name')
                ->values();
        }

        return view('documents.index', [
            'documents' => $documents,
        ]);
    }

    public function download(Request $request, string $document): Response
    {
        $documentPath = $this->resolveDocumentPath($document);

        return response()->download($documentPath);
    }

    public function destroy(Request $request, string $document): RedirectResponse
    {
        $documentPath = $this->resolveDocumentPath($document);

        File::delete($documentPath);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document supprimé avec succès.');
    }

    private function resolveDocumentPath(string $document): string
    {
        $safeName = basename($document);
        $documentPath = public_path('documents/' . $safeName);

        if (! File::exists($documentPath) || ! File::isFile($documentPath)) {
            abort(404);
        }

        return $documentPath;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go', 'To'];
        $index = 0;
        $size = $bytes;

        while ($size >= 1024 && $index < count($units) - 1) {
            $size /= 1024;
            $index++;
        }

        return sprintf('%s %s', rtrim(rtrim(number_format($size, 1), '0'), '.'), $units[$index]);
    }
}
