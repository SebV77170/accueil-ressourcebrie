<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

        $postMaxSize = $this->parseSizeToBytes((string) ini_get('post_max_size'));
        $uploadMaxSize = $this->parseSizeToBytes((string) ini_get('upload_max_filesize'));
        $uploadLimitBytes = collect([$postMaxSize, $uploadMaxSize])
            ->filter(fn (int $value) => $value > 0)
            ->min() ?? 0;

        return view('documents.index', [
            'documents' => $documents,
            'uploadLimitBytes' => $uploadLimitBytes,
            'uploadLimitLabel' => $uploadLimitBytes > 0 ? $this->formatBytes($uploadLimitBytes) : null,
        ]);
    }

    public function download(Request $request, string $document): BinaryFileResponse
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

    public function store(Request $request): RedirectResponse
    {
        /** @var UploadedFile|null $file */
        $file = $request->file('document');

        if (! $file || ! $request->hasFile('document')) {
            $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
            $postMaxSize = $this->parseSizeToBytes((string) ini_get('post_max_size'));

            if ($contentLength > 0 && $postMaxSize > 0 && $contentLength > $postMaxSize) {
                return redirect()
                    ->route('documents.index')
                    ->with('status', "Le document dépasse la limite d'envoi du serveur ({$this->formatBytes($postMaxSize)}).");
            }

            return redirect()
                ->route('documents.index')
                ->with('status', 'Aucun document sélectionné.');
        }

        if (! $file->isValid()) {
            return redirect()
                ->route('documents.index')
                ->with('status', "Le document n'a pas pu être envoyé (taille ou configuration serveur).");
        }

        $documentsPath = public_path('documents');
        File::ensureDirectoryExists($documentsPath);

        $filename = $this->resolveUniqueFilename($documentsPath, $file->getClientOriginalName());

        $file->move($documentsPath, $filename);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document importé avec succès.');
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

    private function resolveUniqueFilename(string $documentsPath, string $originalName): string
    {
        $safeName = basename($originalName);
        $extension = pathinfo($safeName, PATHINFO_EXTENSION);
        $name = pathinfo($safeName, PATHINFO_FILENAME);

        $candidate = $safeName;
        $counter = 1;

        while (File::exists($documentsPath . DIRECTORY_SEPARATOR . $candidate)) {
            $suffix = sprintf('%s-%d', $name, $counter);
            $candidate = $extension !== '' ? $suffix . '.' . $extension : $suffix;
            $counter++;
        }

        return $candidate;
    }

    private function parseSizeToBytes(string $size): int
    {
        $normalized = trim($size);

        if ($normalized === '') {
            return 0;
        }

        $unit = strtolower(substr($normalized, -1));
        $value = (int) $normalized;

        return match ($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => (int) $normalized,
        };
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
